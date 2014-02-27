package Playnet::Content::PAPER_HCMotd;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'13'} = \&handleContent;
	
	### add statements
	#&addStatement('game_db','major_contested_city_select',q{select c.cp_oid,c.name,count(f.facility_oid) as facilities from strat_cp c, strat_facility f where c.cp_type != 5 and c.contention = 1 and c.cp_oid = f.cp_oid group by c.cp_oid order by facilities DESC limit 1});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	$vars->{'MOTD'} = 'Message of the Day.';
	
	return 1;

}

1;
