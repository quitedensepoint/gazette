package Playnet::Content::PAPER_PassThrough;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	$main::source_vars{'21'} = \&handleContent;
	$main::source_vars{'46'} = \&handleContent;
	
}

sub handleContent(){
	return 1;
}

1;
