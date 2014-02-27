package Playnet::Content::Common;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

@ISA 		= qw(Exporter);
@EXPORT 	= qw(initCommonHandlers);

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	&addStatement('game_db','any_city_select',q{SELECT c.name FROM strat_cp WHERE cp_type != 5 ORDER BY RAND() LIMIT 1});
	&addStatement('game_db','any_contested_city_select',q{select name from strat_cp where cp_type != 5 and contention = 1 order by RAND() limit 1});
	&addStatement('game_db','any_front_city_select',q{SELECT distinct c.name FROM strat_link l, strat_object o, strat_facility f, strat_cp c, strat_object oo, strat_facility of, strat_cp oc WHERE l.startdepot_oid = o.object_oid AND o.facility_oid = f.facility_oid AND f.cp_oid = c.cp_oid AND l.enddepot_oid = oo.object_oid AND oo.facility_oid = of.facility_oid AND of.cp_oid = oc.cp_oid AND oc.side != c.side ORDER BY RAND() LIMIT 1});
	&addStatement('game_db','city_select',q{select name from strat_cp where country = ? and cp_type != 5 order by RAND() limit 1});
	&addStatement('game_db','front_city_select',q{SELECT distinct c.name FROM strat_link l, strat_object o, strat_facility f, strat_cp c, strat_object oo, strat_facility of, strat_cp oc WHERE l.startdepot_oid = o.object_oid AND o.facility_oid = f.facility_oid AND f.cp_oid = c.cp_oid AND c.country = ? AND l.enddepot_oid = oo.object_oid AND oo.facility_oid = of.facility_oid AND of.cp_oid = oc.cp_oid AND oc.side != c.side ORDER BY RAND() LIMIT 1});
	&addStatement('game_db','contested_city_select',q{select name from strat_cp where country = ? and cp_type != 5 and contention = 1 order by RAND() limit 1});
	&addStatement('game_db','port_city_select',q{select distinct c.name from strat_cp c, strat_facility f where c.country = ? and c.cp_oid = f.cp_oid and f.facility_type in (6,10) order by RAND() limit 1});
	&addStatement('game_db','factory_city_select',q{select distinct c.name from strat_cp c, strat_facility f where c.country = ? and c.cp_oid = f.cp_oid and f.facility_type = '2' order by RAND() limit 1});
	#&addStatement('game_db','bridge_city_select',q{select distinct c.name from strat_cp c, strat_facility f where c.country = ? and c.cp_oid = f.cp_oid and f.facility_type = '2' order by RAND() limit 1});
	&addStatement('community_db','reason_select',q{select r.description from reasons r, reason_sources rrt, reason_countries rrc, reason_classes rrcl where r.reason_id = rrt.reason_id and rrt.source_id = ? and r.reason_id = rrc.reason_id and rrc.country_id = ? and r.reason_id = rrcl.reason_id and rrcl.class_id = ? order by RAND() limit 1});
	&addStatement('game_db','enemy_city_select',q{select name from strat_cp where side != ? and cp_type != 5 order by RAND() limit 1});
	&addStatement('game_db','enemy_front_city_select',q{SELECT distinct c.name FROM strat_link l, strat_object o, strat_facility f, strat_cp c, strat_object oo, strat_facility of, strat_cp oc WHERE l.startdepot_oid = o.object_oid AND o.facility_oid = f.facility_oid AND f.cp_oid = c.cp_oid AND c.side != ? AND l.enddepot_oid = oo.object_oid AND oo.facility_oid = of.facility_oid AND of.cp_oid = oc.cp_oid AND oc.side != c.side ORDER BY RAND() LIMIT 1});
	&addStatement('game_db','enemy_contested_city_select',q{select name from strat_cp where side != ? and cp_type != 5 and contention = 1 order by RAND() limit 1});
	&addStatement('game_db','enemy_port_city_select',q{select distinct c.name from strat_cp c, strat_facility f where c.side != ? and c.cp_oid = f.cp_oid and f.facility_type in (6,10) order by RAND() limit 1});
	&addStatement('game_db','enemy_factory_city_select',q{select distinct c.name from strat_cp c, strat_facility f where c.side != ? and c.cp_oid = f.cp_oid and f.facility_type = '2' order by RAND() limit 1});
	#&addStatement('game_db','enemy_bridge_select',q{select * from});
	&addStatement('community_db','enemy_country_select',q{select name from paper_countries where side_id != ? order by RAND() limit 1});
	&addStatement('community_db','enemy_country_adj_select',q{select adjective from paper_countries where side_id != ? order by RAND() limit 1});
	&addStatement('community_db','enemy_side_select',q{select side from paper_countries where side_id != ? order by RAND() limit 1});
	&addStatement('community_db','enemy_vehicle_select',q{select v.name from paper_vehicles v, paper_countries c where c.side_id != ? and c.country_id = v.country_id and v.branch_id = ? and v.class_id = ? order by RAND() limit 1});
	
	&addStatement('community_db','date_select',q{select DATE_FORMAT(NOW(), '%W %M %D, %Y')});
	&addStatement('community_db','day_select',q{select DATE_FORMAT(NOW(), '%W')});
	&addStatement('community_db','day_ord_select',q{select DATE_FORMAT(NOW(), '%D')});
	&addStatement('community_db','month_select',q{select DATE_FORMAT(NOW(), '%M')});
	&addStatement('community_db','year_select',q{select DATE_FORMAT(NOW(), '%Y')});
	&addStatement('community_db','time_select',q{select DATE_FORMAT(NOW(), '%l:%i %p')});
	&addStatement('community_db','military_time_select',q{select DATE_FORMAT(NOW(), '%H:%i')});
	&addStatement('community_db','military_hours_select',q{select DATE_FORMAT(NOW(), '%H00')});
	&addStatement('community_db','paper_control_select',q{select value from paper_control where name = ? limit 1});
	&addStatement('community_db','paper_control_update',q{replace into paper_control (name,value) values(?,?)});	
	
}

my @intensities 	= ('light', 'moderate', 'heavy');
my @directions 		= ('south', 'southeast', 'east','northeast','north','northwest','west','southwest');
my @direction_adjs 	= ('southern', 'southeastern', 'eastern','northeastern','northern','northwestern','western','southwestern');

sub initCommonHandlers(){
	
	my %container = ();
	
	$container{'DATE'}					= \&handleDate;
	$container{'DAY'}					= \&handleDay;
	$container{'DAY_ORD'}				= \&handleDayOrd;
	$container{'MONTH'}					= \&handleMonth;
	$container{'YEAR'}					= \&handleYear;
	$container{'TIME'}					= \&handleTime;
	$container{'MILITARY_TIME'}			= \&handleMilitaryTime;
	$container{'MILITARY_HOURS'}		= \&handleMilitaryHours;
	$container{'ANY_CON_CITY'}			= \&handleAnyContestedCity;
	$container{'ANY_FRONT_CITY'}		= \&handleAnyFrontCity;
	$container{'CITY'}					= \&handleCity;
	$container{'FRONT_CITY'}			= \&handleFrontCity;
	$container{'CON_CITY'}				= \&handleContestedCity;
	$container{'PORT_CITY'}				= \&handlePortCity;
	$container{'FACTORY_CITY'}			= \&handleFactoryCity;
	$container{'BRIDGE'}				= \&handleBridgeCity;
	#$container{'REASON'}				= \&handleReason;
	$container{'VARIETY1'}				= \&handleVariety1;
	$container{'VARIETY2'}				= \&handleVariety2;
	$container{'COUNTRY'}				= \&handleCountry;
	$container{'COUNTRY_ADJ'}			= \&handleCountryAdj;
	$container{'SIDE'}					= \&handleSide;
	$container{'ENEMY_CITY'}			= \&handleEnemyCity;
	$container{'ENEMY_FRONT_CITY'}		= \&handleEnemyFrontCity;
	$container{'ENEMY_CON_CITY'}		= \&handleEnemyContestedCity;
	$container{'ENEMY_PORT_CITY'}		= \&handleEnemyPortCity;
	$container{'ENEMY_FACTORY_CITY'}	= \&handleEnemyFactoryCity;
	$container{'ENEMY_BRIDGE'}			= \&handleEnemyBridge;
	$container{'ENEMY_COUNTRY'}			= \&handleEnemyCountry;
	$container{'ENEMY_COUNTRY_ADJ'}		= \&handleEnemyCountryAdj;
	$container{'ENEMY_SIDE'}			= \&handleEnemySide;
	$container{'ENEMY_VEHICLE'}			= \&handleEnemyVehicle;
	$container{'VEHICLE'}				= \&handleVehicle;
	$container{'CLASS'}					= \&handleClass;
	$container{'CATEGORY'}				= \&handleCategory;
	$container{'BRANCH'}				= \&handleBranch;
	$container{'INTENSITY'}				= \&handleIntensity;
	$container{'DIRECTION'}				= \&handleDirection;
	$container{'DIRECTION_ADJ'}			= \&handleDirectionAdj;
	
	return %container;
}

sub handleAnyCity(){
	return &doSelect("any_city_select",'array_row');
}

sub handleAnyFrontCity(){
	return &doSelect("any_front_city_select",'array_row');
}

sub handleAnyContestedCity(){
	
	my $contested = &doSelect("any_contested_city_select",'array_row');
	
	if(!defined($contested)){
		$contested = &handleAnyFrontCity();
	}
	
	return $contested;
}

sub handleCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("city_select",'array_row',$vars->{'country_id'});
	
	if(!defined($city)){
		$city = &handleAnyFrontCity();
	}
	
	return $city;
}

sub handleFrontCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("front_city_select",'array_row',$vars->{'country_id'});
	
	if(!defined($city)){
		$city = &handleEnemyFrontCity($vars);
	}
	
	return $city;
}

sub handleContestedCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("contested_city_select",'array_row',$vars->{'country_id'});
	
	if(!defined($city)){
		$city = &handleAnyContestedCity();
	}
	
	return $city;
}

sub handlePortCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("port_city_select",'array_row',$vars->{'country_id'});
	
	if(!defined($city)){
		$city = &handleCity($vars);
	}
	
	return $city;
}

sub handleFactoryCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("factory_city_select",'array_row',$vars->{'country_id'});
	
	if(!defined($city)){
		$city = &handleCity($vars);
	}
	
	return $city;
}

sub handleBridgeCity(){
	
	my $vars = shift(@_);
	
	my $city = &handleCity($vars);
	
	return $city;
}

#sub handleReason(){
#	
#	my $vars = shift(@_);
#	
#	return &doSelect("reason_select",'array_row',$vars->{'source_id'},$vars->{'country_id'},$vars->{'class_id'});
#}

sub handleVariety1(){
	
	my $vars = shift(@_);
	
	my @varieties = split(/;/,$vars->{'variety_1'});
	
	return $varieties[&getRandomNumber(0, scalar(@varieties) - 1)];
}

sub handleVariety2(){
	
	my $vars = shift(@_);
	
	my @varieties = split(/;/,$vars->{'variety_2'});
	
	return $varieties[&getRandomNumber(0, scalar(@varieties) - 1)];
}

sub handleCountry(){
	
	my $vars = shift(@_);
	
	return $vars->{'country'};
}

sub handleCountryAdj(){
	
	my $vars = shift(@_);
	
	return $vars->{'country_adj'};
}

sub handleSide(){
	
	my $vars = shift(@_);
	
	return $vars->{'side'};
}

sub handleEnemyCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("enemy_city_select",'array_row',$vars->{'side_id'});
	
	if(!defined($city)){
		$city = &handleAnyCity($vars);
	}
	
	return $city;
}

sub handleEnemyFrontCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("enemy_front_city_select",'array_row',$vars->{'side_id'});
	
	if(!defined($city)){
		$city = &handleEnemyCity($vars);
	}
	
	return $city;
}

sub handleEnemyContestedCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("enemy_contested_city_select",'array_row',$vars->{'side_id'});
	
	if(!defined($city)){
		$city = &handleEnemyFrontCity($vars);
	}
	
	return $city;
}

sub handleEnemyPortCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("enemy_port_city_select",'array_row',$vars->{'side_id'});
	
	if(!defined($city)){
		$city = &handleEnemyCity($vars);
	}
	
	return $city;
}

sub handleEnemyFactoryCity(){
	
	my $vars = shift(@_);
	
	my $city = &doSelect("enemy_factory_city_select",'array_row',$vars->{'side_id'});
	
	if(!defined($city)){
		$city = &handleEnemyCity($vars);
	}
	
	return $city;
}

sub handleEnemyBridge(){
	
	my $vars = shift(@_);
	
	my $city = &handleEnemyCity($vars);
	
	return $city;
}

sub handleEnemyCountry(){
	
	my $vars = shift(@_);
	
	return &doSelect("enemy_country_select",'array_row',$vars->{'side_id'});
}

sub handleEnemyCountryAdj(){
	
	my $vars = shift(@_);
	
	return &doSelect("enemy_country_adj_select",'array_row',$vars->{'side_id'});
}

sub handleEnemySide(){
	
	my $vars = shift(@_);
	
	return &doSelect("enemy_side_select",'array_row',$vars->{'side_id'});
}

sub handleEnemyVehicle(){
	
	my $vars = shift(@_);
	
	return &doSelect("enemy_vehicle_select",'array_row',$vars->{'side_id'},$vars->{'branch_id'},$vars->{'class_id'});
}

sub handleVehicle(){
	
	my $vars = shift(@_);
	
	return $vars->{'vehicle'};
}

sub handleClass(){
	
	my $vars = shift(@_);
	
	return $vars->{'class'};
}

sub handleCategory(){
	
	my $vars = shift(@_);
	
	return $vars->{'category'};
}

sub handleBranch(){
	
	my $vars = shift(@_);
	
	return $vars->{'branch'};
}

sub handleIntensity(){
	return $intensities[&getRandomNumber(0,scalar(@intensities) - 1)];
}

sub handleDirection(){
	return $directions[&getRandomNumber(0,scalar(@intensities) - 1)];
}

sub handleDirectionAdj(){
	return $direction_adjs[&getRandomNumber(0,scalar(@intensities) - 1)];
}

sub handleDate(){
	
	## Monday July 4th, 2003
	
	return &doSelect("date_select",'array_row');
	
}

sub handleDay(){
	
	## Monday
	
	return &doSelect("day_select",'array_row');
}

sub handleDayOrd(){

	## 4th
	
	return &doSelect("day_ord_select",'array_row');
}

sub handleMonth(){
	
	## July
	
	return &doSelect("month_select",'array_row');
}

sub handleYear(){
	
	## 2003
	
	return &doSelect("year_select",'array_row');
}

sub handleTime(){
	
	## 4:55PM
	
	return &doSelect("time_select",'array_row');
}

sub handleMilitaryTime(){
	
	## 23:00
	
	return &doSelect("military_time_select",'array_row');
	
}

sub handleMilitaryHours(){
	
	## 23:00
	
	return &doSelect("military_hours_select",'array_row');
	
}


1;
