#!/usr/bin/perl -w

## Standard Modules
use strict;
use MIME::Lite;
use Data::Dumper;

use lib ('/usr/local/community/scoring'); 
use Playnet::Database;

INIT {
	
	if(!&addDatabase('community_db',"dbi:mysql:database=community;host=db2.wwiionline.com",'community','fun4all')){
		die "Unable to connect to ScoringDB";
	}
	
	#########################################
	# Services Queries
	#########################################
	
	&addStatement('community_db','campaign_select',q{
		SELECT campaign_id,
			sorties,
			kills,
			deaths,
			captures,
			rtbs,
			rescues,
			mias,
			kias,
			tom,
			start_time
		FROM scoring_campaigns
		WHERE ISNULL(stop_time)
		ORDER BY start_time DESC
		LIMIT 1
	});
	
	&addStatement('community_db','countries_select',q{
		SELECT c.country_id,
			c.sorties,
			c.kills,
			c.deaths,
			c.captures,
			c.rtbs,
			c.rescues,
			c.mias,
			c.kias,
			c.tom,
			co.name
		FROM scoring_campaign_countries c,
			scoring_countries co
		WHERE co.country_id = c.country_id
		ORDER BY c.sorties DESC
	});
	
	&addStatement('community_db','activity_select',q{
		SELECT hour_id,
			sorties,
			kills,
			deaths,
			captures,
			rtbs,
			rescues,
			mias,
			kias,
			tom
		FROM scoring_activity_hourly
		ORDER BY hour_id ASC
	});
	
	&addStatement('community_db','players_select',q{
		SELECT count(distinct(playerid)) as players
		FROM wwii_persona
		WHERE modified between ? and ?
			AND countryid in (?,?,?)
	});
	
    &addStatement('community_db','sorties_select',q{
		SELECT s.sortie_id,
			s.persona_id,
			s.country_id,
			s.vehicle_id,
			s.kills,
			s.criticals,
			s.captures,
			s.rtb,
			s.tom,
			DATE_FORMAT(s.sortie_start,'%H') as hour,
			p.playerid
		FROM scoring_campaign_sorties s,
			wwii_persona p
		WHERE s.sortie_start between ? and ?
			AND p.personaid = s.persona_id
		ORDER BY s.sortie_start ASC
    });
    
    MIME::Lite->send('smtp', 'mail.playnet.com', 'Timeout' => 10);
}

my $day			= 86400;
my $start		= &makeDateString(time - $day);			# '2004-10-19 00:00:00'
my $stop		= &makeDateString(time);			# '2004-10-20 00:00:00'

my $report		= '';
my $save		= "/tmp/aar_$start.txt";

my $campaign 	= &doSelect('campaign_select','hashref_row');
my $activity	= &doSelect('activity_select','hashref_all');
my $countries	= &loadCountries();

foreach my $sortie (@{&doSelect('sorties_select','hashref_all', "$start 00:00:00", "$stop 00:00:00")})
{
	my $country = $countries->{$sortie->{'country_id'}};
	
	if($country)
	{
		if(!$country->{'activity'}->{$sortie->{'hour'}})
		{
			$country->{'activity'}->{$sortie->{'hour'}} = &createTemplate();
			$country->{'activity'}->{$sortie->{'hour'}}->{'players'} = {};
		}
		
		$country->{'activity'}->{$sortie->{'hour'}}->{'players'}->{$sortie->{'playerid'}} = 1;
		
		$country->{'activity'}->{$sortie->{'hour'}}->{'sorties'} 		+= 1;
		$country->{'activity'}->{$sortie->{'hour'}}->{'kills'} 			+= $sortie->{'kills'};
		$country->{'activity'}->{$sortie->{'hour'}}->{'deaths'} 		+= ($sortie->{'criticals'} > 0) ? 1: 0;
		$country->{'activity'}->{$sortie->{'hour'}}->{'captures'} 		+= $sortie->{'captures'};
		$country->{'activity'}->{$sortie->{'hour'}}->{'rtbs'} 			+= ($sortie->{'rtb'} == 0) ? 1: 0;
		$country->{'activity'}->{$sortie->{'hour'}}->{'rescues'} 		+= ($sortie->{'rtb'} == 1) ? 1: 0;
		$country->{'activity'}->{$sortie->{'hour'}}->{'mias'} 			+= ($sortie->{'rtb'} == 2) ? 1: 0;
		$country->{'activity'}->{$sortie->{'hour'}}->{'kias'} 			+= ($sortie->{'rtb'} == 3) ? 1: 0;
		$country->{'activity'}->{$sortie->{'hour'}}->{'tom'} 			+= $sortie->{'tom'};
	}
}

$report .= "GLOBAL STATS\n\n";
$report .= "description,players,sorties,kills,deaths,captures,rtbs,rescues,mias,kias,tom\n";

# do global campaign stats

$report .= "Campaign #$campaign->{'campaign_id'},".&doSelect('players_select','array_row',$campaign->{'start_time'},$stop,1,3,4).",".&appendStats($campaign);

# do global country stats

foreach my $cid (keys(%{$countries}))
{
	my $country = $countries->{$cid};
	
	$report .= "$country->{'name'},".&doSelect('players_select','array_row',$campaign->{'start_time'},$stop,$cid,$cid,$cid).",".&appendStats($country);
}

# do global activity stats

foreach my $hour (@{$activity})
{
	$report .= "Hour $hour->{'hour_id'},-,".&appendStats($hour);
}

$report .= "\nDAILY STATS\n\n";
$report .= "description,players,sorties,kills,deaths,captures,rtbs,rescues,mias,kias,tom\n";

# do daily campaign

my $stats = &createTemplate();

$stats->{'players'}	= {};

foreach my $cid (keys(%{$countries}))
{
	my $country = $countries->{$cid};
	
	foreach my $hour_id (keys(%{$country->{'activity'}}))
	{
		my $hour = $country->{'activity'}->{$hour_id};
		
		&sumStats($hour, $stats);
		
		foreach my $pid (keys(%{$hour->{'players'}}))
		{
			$stats->{'players'}->{$pid} = 1;
		}
	}
}

$report .= "Campaign #$campaign->{'campaign_id'},".&playerCount($stats->{'players'}).",".&appendStats($stats);

undef($stats);

# do daily countries

foreach my $cid (keys(%{$countries}))
{
	my $country = $countries->{$cid};
	my $stats	= &createTemplate();
	
	$stats->{'players'}	= {};
	
	foreach my $hour_id (keys(%{$country->{'activity'}}))
	{
		my $hour = $country->{'activity'}->{$hour_id};
		
		&sumStats($hour, $stats);
		
		foreach my $pid (keys(%{$hour->{'players'}}))
		{
			$stats->{'players'}->{$pid} = 1;
		}
	}
	
	$report .= "$country->{'name'},".&playerCount($stats->{'players'}).",".&appendStats($stats);
}

# do daily activity

foreach my $cid (keys(%{$countries}))
{
	my $country = $countries->{$cid};
	
	$report .= "\nDAILY ".uc($country->{'name'})." ACTIVITY STATS\n\n";
	$report .= "description,players,sorties,kills,deaths,captures,rtbs,rescues,mias,kias,tom\n";
	
	foreach my $hour_id (sort(keys(%{$country->{'activity'}})))
	{
		my $hour = $country->{'activity'}->{$hour_id};
		
		$report .= "Hour $hour_id,".&playerCount($hour->{'players'}).",".&appendStats($hour);
	}
}

open(SAVE, ">$save") or die;
print SAVE $report;
close(SAVE);

my $msg = MIME::Lite->new
(
	'To' 		=> 'gophur@playnet.com',
	'From' 		=> 'aars@wwiionline.com',
	'Cc'		=> 'ramp@playnet.com',
  	'Subject' 	=> "Campaign #$campaign->{'campaign_id'} AAR $start",
	'Type' 		=> 'text/plain',
	'Data' 		=> "Report attached."
);

$msg->attach
(
	'Type'     		=> 'text/plain',
	'Path'     		=> $save,
	'Disposition' 	=> 'attachment'
);

$msg->send();

&freeDatabases();

exit(0);

sub makeDateString(){
	
	my @stamp = localtime(shift);
	
	#  0    1    2     3     4    5     6     7     8
    #($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) =
	#					localtime(time);  
	
	#'2003-01-25 04:55:34'
	
	#return ($stamp[5] + 1900).'-'.($stamp[4] + 1).'-'.$stamp[3].' 00:00:00';		# .$stamp[2].':'.$stamp[1].':'.$stamp[0]
	return ($stamp[5] + 1900).'-'.($stamp[4] + 1).'-'.$stamp[3];		# .$stamp[2].':'.$stamp[1].':'.$stamp[0]
}

sub createTemplate()
{
	return {
		'sorties'	=> 0,
		'kills'		=> 0,
		'damages'	=> 0,
		'captures'	=> 0,
		'rtbs'		=> 0,
		'rescues'	=> 0,
		'mias'		=> 0,
		'kias'		=> 0,
		'tom'		=> 0
	};
}

sub loadCountries()
{
	my $cs = {};
	
	foreach my $c (@{&doSelect('countries_select','hashref_all')})
	{
		$c->{'activity'} = {};
		
		$cs->{$c->{'country_id'}} = $c;
	}
	
	return $cs;
}

sub appendStats()
{
	my $data 	= shift;
	my $string 	= $data->{'sorties'};
	
	$string .= ",$data->{'kills'}";
	$string .= ",$data->{'deaths'}";
	$string .= ",$data->{'captures'}";
	$string .= ",$data->{'rtbs'}";
	$string .= ",$data->{'rescues'}";
	$string .= ",$data->{'mias'}";
	$string .= ",$data->{'kias'}";
	$string .= ",$data->{'tom'}\n";
	
	return $string;
}

sub sumStats()
{
	my $from	= shift;
	my $to		= shift;
	
	if($from and $to)
	{
		$to->{'sorties'} 		+= $from->{'sorties'};
		$to->{'kills'} 			+= $from->{'kills'};
		$to->{'deaths'} 		+= $from->{'deaths'};
		$to->{'captures'} 		+= $from->{'captures'};
		$to->{'rtbs'} 			+= $from->{'rtbs'};
		$to->{'rescues'} 		+= $from->{'rescues'};
		$to->{'mias'} 			+= $from->{'mias'};
		$to->{'kias'} 			+= $from->{'kias'};
		$to->{'tom'} 			+= $from->{'tom'};
	}
}

sub playerCount()
{
	my $players = shift;
	
	return scalar(keys(%{$players}));
}
