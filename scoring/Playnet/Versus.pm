package Playnet::Versus;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updatePersonaVersus updateGlobalVersus saveVersus);

my $inited 	= 0;
my %versus	= (); ## Holds Persona -> Vehicle -> Opponent Vehicle Sortie Stats
my %global	= (); ## Holds Global -> Vehicle -> Opponent Vehicle Sortie Stats

sub updatePersonaVersus(){
	
	my $pid 		= shift(@_);
	my $kpid		= shift(@_);
	my $vid 		= shift(@_);
	my $kvid 		= shift(@_);
	my $awarded		= shift(@_);
	
	&initQueries();
	
	### Setup Base Persona
	if(!exists($versus{$pid})){
		$versus{$pid} = {};
	}
	
	### Setup opponent persona
	if(!exists($versus{$kpid})){
		$versus{$kpid} = {};
	}
	
	### Add in the current sortie's vehicle, we do it this way because the persona could
	### already be loaded, we start with a an opponent of 0 which means no opponent
	if(!exists($versus{$pid}->{$vid})){
		$versus{$pid}->{$vid} = {};
	}
	
	### Setup opponent persona vehicle
	if(!exists($versus{$kpid}->{$kvid})){
		$versus{$kpid}->{$kvid} = {};
	}
	
	### Setup persona vehicle to opponent vehicle
	if(!exists($versus{$pid}->{$vid}->{$kvid})){
		$versus{$pid}->{$vid}->{$kvid} = &getVersusTemplate();
	}
	
	### Setup opponent persona opponent vehicle to vehicle
	if(!exists($versus{$kpid}->{$kvid}->{$vid})){
		$versus{$kpid}->{$kvid}->{$vid} = &getVersusTemplate();
	}
	
	$versus{$pid}->{$vid}->{$kvid}->{'kills'} += 1;
	
	if(!$awarded){
		$versus{$kpid}->{$kvid}->{$vid}->{'deaths'}	+= 1;
	}
	
}

sub updateGlobalVersus(){
	
	my $vid 	= shift(@_);
	my $kvid 	= shift(@_);
	
	&initQueries();
	
	### Setup Global Vehicle, we start with a an opponent of 0 which means no opponent
	if(!exists($global{$vid})){
		$global{$vid} = {};
	}
	
	### Setup global opponent vehicle
	if(!exists($global{$kvid})){
		$global{$kvid} = {};
	}
	
	### Setup global vehicle to opponent vehicle
	if(!exists($global{$vid}->{$kvid})){
		$global{$vid}->{$kvid} = &getVersusTemplate();
	}
	
	### Setup global opponent vehicle to vehicle
	if(!exists($global{$kvid}->{$vid})){
		$global{$kvid}->{$vid} = &getVersusTemplate();
	}
	
	$global{$vid}->{$kvid}->{'kills'}	+= 1;
	$global{$kvid}->{$vid}->{'deaths'}	+= 1;
	
}

sub saveVersus(){
	
	foreach my $pid (keys(%versus)){
		
		foreach my $vid (keys(%{$versus{$pid}})){
			
			foreach my $ovid (keys(%{$versus{$pid}->{$vid}})){
				
				my $result = $versus{$pid}->{$vid}->{$ovid};
				
				#print "     Persona Versus $pid/$vid/$ovid/".$result->{'kills'}."/".$result->{'deaths'}."\n";
				
				if(!&doUpdate('persona_versus_update',$result->{'kills'},$result->{'deaths'},$pid,$vid,$ovid)){
					&doUpdate('persona_versus_insert',$pid,$vid,$ovid,$result->{'kills'},$result->{'deaths'});
				}
				
				&doUpdate('persona_versus_kd_update',$pid,$vid,$ovid);
				
			}
			
		}
		
	}
	
	foreach my $vid (keys(%global)){
		
		foreach my $ovid (keys(%{$global{$vid}})){
			
			my $result = $global{$vid}->{$ovid};
			
			#print "     Global Versus $vid/$ovid/".$result->{'kills'}."/".$result->{'deaths'}."\n";
			
			if(!&doUpdate('versus_update',$result->{'kills'},$result->{'deaths'},$vid,$ovid)){
				&doUpdate('versus_insert',$vid,$ovid,$result->{'kills'},$result->{'deaths'});
			}
			
			&doUpdate('versus_kd_update',$vid,$ovid);
			
		}
		
	}
	
}

sub initQueries(){
	
	if(!$inited){
		
		&addStatement('community_db','persona_versus_insert',q{
			INSERT INTO scoring_campaign_versus (persona_id, vehicle_id, opponent_vehicle_id, kills, deaths, kd, modified)
			VALUES (?,?,?,?,?,0,NOW())
		});
		
		&addStatement('community_db','persona_versus_update',q{
			UPDATE scoring_campaign_versus
			SET kills = kills + ?,
				deaths = deaths + ?,
				modified = NOW()
			WHERE persona_id = ? AND vehicle_id = ? AND opponent_vehicle_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','persona_versus_kd_update',q{
			UPDATE scoring_campaign_versus
			SET kd = if(deaths > 0, kills / deaths, kills)
			WHERE persona_id = ? AND vehicle_id = ? AND opponent_vehicle_id = ?
			LIMIT 1
		});
		
		## Versus
		&addStatement('community_db','versus_insert',q{
			INSERT INTO scoring_vehicle_versus_campaign (vehicle_id, opponent_vehicle_id, kills, deaths, kd, modified)
			VALUES (?,?,?,?,0,NOW())
		});
		
		&addStatement('community_db','versus_update',q{
			UPDATE scoring_vehicle_versus_campaign
			SET kills = kills + ?,
				deaths = deaths + ?,
				modified = NOW()
			WHERE vehicle_id = ? AND opponent_vehicle_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','versus_kd_update',q{
			UPDATE scoring_vehicle_versus_campaign
			SET kd = if(deaths > 0, kills / deaths, kills)
			WHERE vehicle_id = ? AND opponent_vehicle_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

sub getVersusTemplate(){

	my %template = ();
	
	$template{'kills'} 		= 0;
	$template{'deaths'} 	= 0;
	
	return \%template;
	
}

1;
