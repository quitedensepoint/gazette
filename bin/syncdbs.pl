#!/usr/bin/perl -w

## Standard Modules
use strict;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

our $LOGFILE;

INIT
{

  if (0)
	{ open(our $LOGFILE, ">&STDOUT"); }
  else
	{ open(our $LOGFILE, '>>', "/usr/local/community/logs/syncd.log") || die "problem opening log file\n"; }
  &useLogFile($LOGFILE);
	
	if(!&addDatabase('community_db',"dbi:mysql:database=community;host=localhost",'community','fun4all')){ #CP111713 changed csr to localhost
		die "Unable to connect to ServicesDB";
	}
	
	if(!&addDatabase('game_db',"dbi:mysql:database=wwiionline;host=gamedbu.wwiionline.com",'wwiiol','freebird')){
		die "Unable to connect to GameDBS";
	}
	
	#########################################
	# Services Queries
	#########################################
	
	&addStatement('community_db','last_modified_select',q{
		SELECT max(pl.modified) as modified_player, max(pe.modified) as modified_persona FROM wwii_player pl JOIN wwii_persona pe
	});
	
	&addStatement('game_db','modified_players_select',q{
		SELECT playerid,customerid,callsign,modified,added,lockside,squadid,locktime,isplaynet,player_decal FROM wwii_player where modified > ? order by modified ASC limit 500
	});
	
	&addStatement('game_db','modified_personas_select',q{
		SELECT playerid,countryid,branchid,rankid,modified,added,usedmissnpts,rankpoints,personaid FROM wwii_persona where modified > ? order by modified ASC limit 500
	});
	
	#&addStatement('game_db','modified_players_select',q{
	#	SELECT * FROM wwii_player where customerid = 1581
	#});
	
	#&addStatement('game_db','modified_personas_select',q{
	#	SELECT * FROM wwii_persona where personaid = 112
	#});
	
	&addStatement('community_db','player_replace',q{
		REPLACE INTO wwii_player (playerid,customerid,callsign,modified,added,lockside,squadid,locktime,isplaynet,player_decal)
		VALUES (?,?,?,?,?,?,?,?,?,?)
	});
	
	&addStatement('community_db','persona_replace',q{
		REPLACE INTO wwii_persona (playerid,countryid,branchid,rankid,modified,added,usedmissnpts,rankpoints,personaid)
		VALUES (?,?,?,?,?,?,?,?,?)
	});
	
}

my %sysvars = &startScoring();

my $players = &doSelect('modified_players_select','arrayref_all', $sysvars{'last_check'}->{'modified_player'});
my $total 	= scalar(@{$players});
my $updated	= 0;

print $LOGFILE "Processing $total Players from ".$sysvars{'last_check'}->{'modified_player'}."...";

foreach my $player (@{$players}){
	if(&doUpdate('player_replace', @{$player})){
		$updated++;
	}
}

print $LOGFILE "$updated updated.\n";

my $personas 	= &doSelect('modified_personas_select','arrayref_all', $sysvars{'last_check'}->{'modified_persona'});

$total 			= scalar(@{$personas});
$updated		= 0;

print $LOGFILE "Processing $total Personas from ".$sysvars{'last_check'}->{'modified_persona'}."...";

foreach my $persona (@{$personas}){
	if(&doUpdate('persona_replace', @{$persona})){
		$updated++;
	}
}

print $LOGFILE "$updated updated.\n";

&freeDatabases();

exit(0);

sub startScoring(){
	
	my %vars = ();
	
	$vars{'last_check'}	= &doSelect('last_modified_select','hashref_row');
	
	return %vars;
	
}

sub pauseForInput(){
	
	return;
	
	my $message = shift(@_);
	
	print "\nDebug Pause: $message\n\n";
	
	print "[PRESS ENTER TO CONTINUE]\n";
	
	<STDIN>;
	
}
