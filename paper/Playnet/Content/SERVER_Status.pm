package Playnet::Content::SERVER_Status;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'20'} = \&handleContent;
	
	### add statements
	&addStatement('auth_db','20_status_select',q{select count(*) from auth_status where arena_id = 1 and visibility = 1 and status = 0});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $online = &doSelect('20_status_select','array_row');
	
	$vars->{'STATUS'} = ($online > 0) ? '<img src="http://www.mirror.wwiionline.com/images/playnow/playnowbutton_serverup.gif" width="142" height="16">': '<img src="http://www.mirror.wwiionline.com/images/playnow/playnowbutton_serverdown.gif" width="142" height="16">';
	
	return 1;

}

1;
