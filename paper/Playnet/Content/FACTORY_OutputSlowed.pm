package Playnet::Content::FACTORY_OutputSlowed;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'22'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','22_factories_select',q{select * from paper_factories where ocountry_id = ?});

}

sub handleContent()
{
	my $vars 		= shift(@_);
	my $factories 	= &doSelect('22_factories_select','hashref_all',$vars->{'country_id'});
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
		my $health		= 0;
		my $damage		= 0;
		
		foreach my $factory (keys(%{$cps{$cp}}))
		{
			$producers	+= 1;
			$health		+= 100;
			
			if($cps{$cp}->{$factory}->{'status'} eq 'Captured')
			{
				$damage		+= 100;
			}
			else
			{
				$damage		+= $cps{$cp}->{$factory}->{'damaged'};
			}
		}
		
		if($health - $damage != $health)
		{
			$vars->{'FACTORY_CITY'} 	= $cp;
			$vars->{'FACTORY_DAMAGE'} 	= int(($damage / $health) * 100);
			$vars->{'FACTORY_OUTPUT'} 	= int(($health - $damage) / $producers);
			
			return 1;
		}
	}
	
	return 0;
}

1;
