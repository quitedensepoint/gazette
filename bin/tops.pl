#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

INIT {
	
	if(!&addDatabase('community_db',"dbi:mysql:database=community;host=66.28.224.237",'community','fun4all')){
		die "Unable to connect to ScoringDB";
	}
	
	#########################################
	# Services Queries
	#########################################
	
	&addStatement('community_db','lists_select',q{
		SELECT * FROM scoring_tops
	});
	
	&addStatement('community_db','top_persona_insert',q{
		REPLACE INTO scoring_top_personas (list_id,rank,persona_id,last_rank,value1,value2,value3,value4,value5,value6)
		VALUES (?,?,?,?,?,?,?,?,?,?)
	});
	
	&addStatement('community_db','last_rank_select',q{
		SELECT rank
		FROM scoring_top_personas
		WHERE list_id = ? AND persona_id = ?
		LIMIT 1
	});
	
	&addStatement('community_db','extras_delete',q{
		DELETE FROM scoring_top_personas
		WHERE list_id = ? AND rank > ?
	});
	
}

my $do_list = (defined($ARGV[0])) ? $ARGV[0]: 0;

chomp($do_list);

print "List is $do_list.\n";

my %sysvars = &startScoring();

############ DO Tops by Streak #############

my $lists = &doSelect('lists_select','hashref_all');

foreach my $list (@{$lists}){
	
	if($do_list > 0 and $list->{'list_id'} != $do_list){
		print "Skipping list ".$list->{'list_id'}.".\n";
		next;
	}
	
	print "Processing List ".$list->{'short_title'}." (".$list->{'list_id'}.") ...\n";
	
	my $rank 	= 1;
	my $mark	= time;
	my $tops 	= &doQuery('community_db', $list->{'list_id'}.'_primary_select', $list->{'primary_query'}, 'hashref_all');
	
	push(@{$sysvars{'times'}}, 'List '.$list->{'short_title'}.' took '.(time - $mark)." seconds to complete.\n");
	
	foreach my $top (@{$tops}){
		
		$top->{'last_rank'} = &doSelect('last_rank_select', 'array_row', $list->{'list_id'}, $top->{'persona_id'});
		$top->{'last_rank'} = (!defined($top->{'last_rank'})) ? 0: $top->{'last_rank'};
		
	}
	
	if(defined($list->{'secondary_query'}) and $list->{'secondary_query'} =~ /select/i){
		&addStatement('community_db', $list->{'list_id'}.'_secondary_select', $list->{'secondary_query'});
	}
	
	foreach my $top (@{$tops}){
		
		if(defined($list->{'secondary_query'}) and $list->{'secondary_query'} =~ /select/i){
			
			my $secondary = &doSelect($list->{'list_id'}.'_secondary_select', 'hashref_row', $top->{'persona_id'});
			
			foreach my $key (keys(%{$secondary})){
				$top->{$key} = $secondary->{$key};
			}
			
		}
		
		if(!defined($top->{'value1'})){
			$top->{'value1'} = 0;
		}
		
		if($list->{'min_sorties'} == 0 or $top->{'value1'} >= $list->{'min_sorties'}){
			if(&doUpdate('top_persona_insert', $list->{'list_id'}, $rank, $top->{'persona_id'}, $top->{'last_rank'}, $top->{'value1'}, $top->{'value2'}, $top->{'value3'}, $top->{'value4'}, $top->{'value5'}, $top->{'value6'})){
				print "     ".$rank.'. '.$top->{'persona_id'}.': '.$top->{'last_rank'}.' -> '.$rank."\n";
				$rank++;
			}
		}
		
		if($rank > $sysvars{'limit'}){
			last;
		}
		
	}
	
	if($rank < $sysvars{'limit'}){
		&doUpdate('extras_delete',$list->{'list_id'}, $rank);
	}
	
	sleep(5);
	
}

foreach my $mark (@{$sysvars{'times'}}){
	print $mark;
}

&freeDatabases();

exit(0);

sub startScoring(){
	
	my %vars = ();
	
	$vars{'limit'} 			= 500;
	$vars{'processed'} 		= 0;
	$vars{'process_id'} 	= 1;
	$vars{'times'} 			= [];
	
	return %vars;
	
}
