package Playnet::Content::HEADLINE_CampaignStarted;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'36'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','36_cycles_select',q{select count(*) from paper_campaigns where status = 'Running' and (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(start_time)) < 86400});

}

sub handleContent(){
	
	return 0;
	my $vars = shift(@_);
	
	my $started = &doSelect('36_cycles_select','array_row');
	
	return ($started > 0) ? 1: 0;

}

1;
