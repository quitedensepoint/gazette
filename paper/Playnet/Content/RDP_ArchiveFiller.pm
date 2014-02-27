package Playnet::Content::RDP_ArchiveFiller;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'34'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','34_archive_select',q{select count(*) from paper_countries c, paper_cycle_archives a where c.side_id = ? and c.cycle_id = a.cycle_id});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $big_threshold 	= 6;
	my $small_threshold = 13;
	my $body			= '   ';
	
	my $headlines 		= &doSelect('34_archive_select','array_row',$vars->{'side_id'});
	
	if(defined($headlines) and $headlines > 0 and $headlines < 13){
		
		my @templates = split(/%ALTERNATE%/, $vars->{'body'});
		
		$body = ($headlines < 6) ? $templates[0]: $templates[1];
		
	}
	
	$vars->{'body'} = $body;
	
	return 1;
	
}

1;
