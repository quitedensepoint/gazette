package Playnet::MultiCrews;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateMultiCrews saveMultiCrews);

my $inited 	= 0;
my %crews 	= ();

sub updateMultiCrews(){
	
	my $did 	= shift(@_);
	my $gid 	= shift(@_);
	my $vid 	= shift(@_);
	my $ovid 	= shift(@_);
	my $type	= shift(@_);
	
	&initQueries();
	
	my $mindex = $did."_".$gid;
	
	if(!exists($crews{$mindex})){
		$crews{$mindex} = {};
	}
	
	if(!exists($crews{$mindex}->{$vid})){
		$crews{$mindex}->{$vid} = {};
	}
	
	if(!exists($crews{$mindex}->{$vid}->{$ovid})){
		$crews{$mindex}->{$vid}->{$ovid} = {'kills' => 0, 'deaths' => 0};
	}
	
	if($type eq 'kill'){
		$crews{$mindex}->{$vid}->{$ovid}->{'kills'}	+= 1;
	}
	else{
		$crews{$mindex}->{$vid}->{$ovid}->{'deaths'} += 1;
	}
	
}

sub saveMultiCrews(){
	
	foreach my $mindex (keys(%crews)){
		
		my @personas = split('_', $mindex);
		
		foreach my $vid (keys(%{$crews{$mindex}})){
			
			foreach my $ovid (keys(%{$crews{$mindex}->{$vid}})){
			
				#print "     Crew $pid/$aid/".$adversaries{$pid}->{$aid}->{'kills'}."/".$adversaries{$pid}->{$aid}->{'deaths'}."\n";
				
				my $result = $crews{$mindex}->{$vid}->{$ovid};
				
				if(!&doUpdate('crew_update',$result->{'kills'},$result->{'deaths'},$personas[0],$personas[1],$vid,$ovid)){
					&doUpdate('crew_insert',$personas[0],$personas[1],$vid,$ovid,$result->{'kills'},$result->{'deaths'});
				}
				
				&doUpdate('crew_kd_update',$personas[0],$personas[1],$vid,$ovid);
				
			}
		}
	}

}

sub initQueries(){
	
	if(!$inited){
		
		&addStatement('community_db','crew_insert',q{
			INSERT INTO scoring_campaign_mcversus (driver_id,gunner_id,vehicle_id,opponent_vehicle_id,kills,deaths,kd,modified)
			VALUES (?,?,?,?,?,?,0,NOW())
		});
		
		&addStatement('community_db','crew_update',q{
			UPDATE scoring_campaign_mcversus
			SET kills = ?,
				deaths = ?,
				modified = NOW()
			WHERE driver_id = ?
				AND gunner_id = ?
				AND vehicle_id = ?
				AND opponent_vehicle_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','crew_kd_update',q{
			UPDATE scoring_campaign_mcversus
			SET kd = if(deaths > 0, round(kills / deaths, 2), kills),
				modified = NOW()
			WHERE driver_id = ?
				AND gunner_id = ?
				AND vehicle_id = ?
				AND opponent_vehicle_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

1;
