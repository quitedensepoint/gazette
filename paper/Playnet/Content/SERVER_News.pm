package Playnet::Content::SERVER_News;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'19'} = \&handleContent;
	
	### add statements
	&addStatement('auth_db','19_news_select',q{select motd from auth_status where arena_id = 1 limit 1});
	
}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $news = &doSelect('19_news_select','array_row');
	
	if($news =~ /^$/){
		$news = 'No servers news.';
	}
	
	$news =~ s/\n/<br>\n/g;
	
	$vars->{'NEWS'} = $news;
	
	return 1;

}

1;
