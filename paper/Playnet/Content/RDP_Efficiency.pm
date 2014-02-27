package Playnet::Content::RDP_Efficiency;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT
{
	$main::source_vars{'8'} = \&handleContent;
}

sub handleContent()
{
	return 0;
}

1;
