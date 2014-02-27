package Playnet::Content::FACTORY_UnderAttack;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'24'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','24_factories_select',q{select * from paper_factories where ocountry_id = ?});
	&addStatement('community_db','24_country_select',q{select * from paper_countries where country_id = ?});

}

sub handleContent()
{
	my $vars 		= shift(@_);
	my $factories 	= &doSelect('24_factories_select','hashref_all',$vars->{'country_id'});
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
		
		my $captured	= 0;
		my $capturer	= 0;
		
		foreach my $factory (keys(%{$cps{$cp}}))
		{
			$producers	+= 1;
			$health		+= 100;
			
			if($cps{$cp}->{$factory}->{'status'} eq 'Captured')
			{
				$captured	+= 1;
				$capturer	= $cps{$cp}->{$factory}->{'country_id'};
				$damage		+= 100;
			}
			else
			{
				$damage		+= $cps{$cp}->{$factory}->{'damaged'};
			}
		}
		
		if($captured > 0 and $captured < $producers)
		{
			$vars->{'FACTORY_CITY'} 	= $cp;
			$vars->{'FACTORY_DAMAGE'} 	= int(($damage / $health) * 100);
			$vars->{'FACTORY_OUTPUT'} 	= int(($health - $damage) / $producers);

			$vars->{'PERCENT_ENEMY'} 	= int((($captured * 100) / (100 * $producers)) * 100);
			$vars->{'PERCENT_OWNED'} 	= 100 - $vars->{'PERCENT_ENEMY'};
			
			my $enemy = &doSelect('24_country_select','hashref_row', $capturer);
			
			if($enemy)
			{
				$vars->{'ATTACKING_COUNTRY'}		= $enemy->{'name'};
				$vars->{'ATTACKING_COUNTRY_ADJ'}	= $enemy->{'adjective'};
				$vars->{'ATTACKING_SIDE'}			= $enemy->{'side'};
			}
			
			return 1;
		}
	}
	
	return 0;
}

1;
