package Playnet::Content::FACTORY_Shutdown;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'9'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','9_factories_select',q{select * from paper_factories where ocountry_id = ?});

}

sub handleContent()
{
	my $vars 		= shift(@_);
	my $factories 	= &doSelect('9_factories_select','hashref_all',$vars->{'country_id'});
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
		
		if($health - $damage == 0)
		{
			$vars->{'FACTORY_CITY'} 	= $cp;
			$vars->{'FACTORY_DAMAGE'} 	= 100;
			
			return 1;
		}
	}
	
	return 0;
}

1;
