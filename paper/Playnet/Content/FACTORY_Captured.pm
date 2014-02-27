package Playnet::Content::FACTORY_Captured;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'23'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','23_factories_select',q{select * from paper_factories where ocountry_id = ?});
	&addStatement('community_db','23_country_select',q{select * from paper_countries where country_id = ?});

}

sub handleContent()
{
	my $vars 		= shift(@_);
	my $factories 	= &doSelect('23_factories_select','hashref_all',$vars->{'country_id'});
	my %cps			= ();
	
	foreach my $factory (@{$factories})
	{
		if(!exists($cps{$factory->{'chokepoint'}}))
		{
			$cps{$factory->{'chokepoint'}} = {};
		}
		
		$cps{$factory->{'chokepoint'}}->{$factory->{'factory_id'}} = $factory;
	}
	
	foreach my $cp (keys(%cps))
	{
		my $producers 	= 0;
		my $captured	= 0;
		my $country		= 0;
		
		foreach my $factory (keys(%{$cps{$cp}}))
		{
			$producers	+= 1;
			
			if($cps{$cp}->{$factory}->{'side_id'} != $vars->{'side_id'})
			{
				$captured	+= 1;
				$country	= $cps{$cp}->{$factory}->{'country_id'};
			}
		}
		
		if($producers == $captured)
		{
			$vars->{'FACTORY_CITY'} = $cp;
			
			my $capturer = &doSelect('23_country_select','hashref_row',$country);
			
			if(defined($capturer))
			{
				$vars->{'CAPTURING_COUNTRY'}		= $capturer->{'name'};
				$vars->{'CAPTURING_COUNTRY_ADJ'}	= $capturer->{'adjective'};
				$vars->{'CAPTURING_SIDE'}			= $capturer->{'side'};
			}
			
			return 1;
		}
	}
	
	return 0;

}

1;
