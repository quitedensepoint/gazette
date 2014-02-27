package Playnet::Spawns;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateSpawns saveSpawns);

my $inited = 0;
my %spawns = ();
my %global = ();

sub updateSpawns(){
	
	my $sortie = shift(@_);
	
	&initQueries();
	
	my $pid = $sortie->{'persona'}->{'persona_id'};
	my $vid = $sortie->{'vehicle'}->{'vehicle_id'};
	
	if(!exists($global{$vid})){
		$global{$vid} = &getSpawnTemplate();
	}
	
	if(!exists($spawns{$pid})){
		$spawns{$pid} = {};
	}
	
	if(!exists($spawns{$pid}->{$vid})){
		$spawns{$pid}->{$vid} = &getSpawnTemplate();
	}
	
	$spawns{$pid}->{$vid}->{'sorties'} 	+= 1;
	$spawns{$pid}->{$vid}->{'rtbs'} 	+= ($sortie->{'rtb'} == 0) ? 1: 0;
	$spawns{$pid}->{$vid}->{'rescues'} 	+= ($sortie->{'rtb'} == 1) ? 1: 0;
	$spawns{$pid}->{$vid}->{'mias'} 	+= ($sortie->{'rtb'} == 2) ? 1: 0;
	$spawns{$pid}->{$vid}->{'kias'} 	+= ($sortie->{'rtb'} == 3) ? 1: 0;
	$spawns{$pid}->{$vid}->{'tom'} 		+= $sortie->{'tom'};
	
	$global{$vid}->{'sorties'} 			+= 1;
	$global{$vid}->{'rtbs'} 			+= ($sortie->{'rtb'} == 0) ? 1: 0;
	$global{$vid}->{'rescues'} 			+= ($sortie->{'rtb'} == 1) ? 1: 0;
	$global{$vid}->{'mias'} 			+= ($sortie->{'rtb'} == 2) ? 1: 0;
	$global{$vid}->{'kias'} 			+= ($sortie->{'rtb'} == 3) ? 1: 0;
	$global{$vid}->{'tom'} 				+= $sortie->{'tom'};
	
}

sub saveSpawns(){
	
	foreach my $pid (keys(%spawns)){
		
		foreach my $vid (keys(%{$spawns{$pid}})){
			
			#print "     Persona Spawns $pid/$vid/".$spawns{$pid}->{$vid}->{'sorties'}."/".$spawns{$pid}->{$vid}->{'tom'}."\n";
			
			my $spawn = $spawns{$pid}->{$vid};
			
			if(!&doUpdate('campaign_spawn_update',$spawn->{'sorties'},$spawn->{'rtbs'},$spawn->{'rescues'},$spawn->{'mias'},$spawn->{'kias'},$spawn->{'tom'},$pid,$vid)){
				&doUpdate('campaign_spawn_insert',$pid,$vid,$spawn->{'sorties'},$spawn->{'rtbs'},$spawn->{'rescues'},$spawn->{'mias'},$spawn->{'kias'},$spawn->{'tom'});
			}
			
		}
	}
	
	foreach my $vid (keys(%global)){
		
		#print "     Global Spawns $vid/".$global{$vid}->{'sorties'}."/".$global{$vid}->{'tom'}."\n";
		
		my $spawn = $global{$vid};
		
		if(!&doUpdate('vehicle_spawn_update',$spawn->{'sorties'},$spawn->{'rtbs'},$spawn->{'rescues'},$spawn->{'mias'},$spawn->{'kias'},$spawn->{'tom'},$vid)){
			&doUpdate('vehicle_spawn_insert',$vid,$spawn->{'sorties'},$spawn->{'rtbs'},$spawn->{'rescues'},$spawn->{'mias'},$spawn->{'kias'},$spawn->{'tom'});
		}
		
	}
	
}

sub initQueries(){
	
	if(!$inited){
		
		&addStatement('community_db','campaign_spawn_insert',q{
			INSERT INTO scoring_campaign_spawns (persona_id, vehicle_id, sorties, rtbs, rescues, mias, kias, tom, modified)
			VALUES (?,?,?,?,?,?,?,?,NOW())
		});
		
		&addStatement('community_db','campaign_spawn_update',q{
			UPDATE scoring_campaign_spawns
			SET sorties = sorties + ?,
				rtbs = rtbs + ?,
				rescues = rescues + ?,
				mias = mias + ?,
				kias = kias + ?,
				tom = tom + ?,
				modified = NOW()
			WHERE persona_id = ? AND vehicle_id = ?
			LIMIT 1
		});
		
		## Spawns
		&addStatement('community_db','vehicle_spawn_insert',q{
			INSERT INTO scoring_vehicle_spawns_campaign (vehicle_id, sorties, rtbs, rescues, mias, kias, tom, modified)
			VALUES (?,?,?,?,?,?,?,NOW())
		});
		
		&addStatement('community_db','vehicle_spawn_update',q{
			UPDATE scoring_vehicle_spawns_campaign
			SET sorties = sorties + ?,
				rtbs = rtbs + ?,
				rescues = rescues + ?,
				mias = mias + ?,
				kias = kias + ?,
				tom = tom + ?,
				modified = NOW()
			WHERE vehicle_id = ?
			LIMIT 1
		
		});
		
		$inited = 1;
		
	}
}

sub getSpawnTemplate(){

	my %template = ();
	
	$template{'sorties'} 	= 0;
	$template{'rtbs'} 		= 0;
	$template{'rescues'} 	= 0;
	$template{'mias'} 		= 0;
	$template{'kias'} 		= 0;
	$template{'tom'} 		= 0;
	
	return \%template;
	
}

1;
