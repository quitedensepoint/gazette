package Playnet::Content::RDP_FactoryHealth;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'17'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','17_factories_select',q{select f.*,c.name as country from paper_factories f, paper_countries c where f.oside_id = ? and f.country_id = c.country_id order by f.side_id});
	
}

sub handleContent()
{
	my $vars 		= shift(@_);
	my $factories 	= &doSelect('17_factories_select','hashref_all',$vars->{'side_id'});
	my $body 		= '';
	my $count		= 0;
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
		my $reduced		= 0;
		my $repairs		= 0;
		my $country		= '';
		
		foreach my $factory (keys(%{$cps{$cp}}))
		{
			$producers	+= 1;
			$health		+= 100;
			
			if($cps{$cp}->{$factory}->{'status'} eq 'Captured')
			{
				$captured	+= 1;
				$damage		+= 100;
			}
			elsif($cps{$cp}->{$factory}->{'status'} eq 'Under Repairs')
			{
				$repairs	+= 1;
				$damage		+= $cps{$cp}->{$factory}->{'damaged'};
			}
			elsif($cps{$cp}->{$factory}->{'damaged'} > 0)
			{
				$reduced	+= 1;
				$damage		+= $cps{$cp}->{$factory}->{'damaged'};
			}
			
			$country = $cps{$cp}->{$factory}->{'country'};
		}
		
		my $fvars = {};
		
		$fvars->{'FACTORY_CP'} 		= $cp;
		$fvars->{'FACTORY_COUNTRY'} = $country;
		
		$fvars->{'FACTORY_DAMAGE'} 	= int(($damage / $health) * 100);
		$fvars->{'FACTORY_OUTPUT'} 	= int(($health - $damage) / $producers);
		
		if($captured == $producers)
		{
			$fvars->{'FACTORY_STATUS'} 	= 'Captured';
		}
		elsif($captured > 0)
		{
			$fvars->{'FACTORY_STATUS'} 	= 'Under Attack';
		}
		elsif($repairs > 0)
		{
			$fvars->{'FACTORY_STATUS'} 	= 'Under Repairs';
		}
		elsif($reduced > 0)
		{
			$fvars->{'FACTORY_STATUS'} 	= 'Reduced Production';
		}
		else
		{
			$fvars->{'FACTORY_STATUS'} 	= 'Full Production';
		}
		
		$body .= &miniParser($vars->{'body'}, $fvars);
	}
	
	$vars->{'body'} = $body;
	
	return 1;
}

1;
