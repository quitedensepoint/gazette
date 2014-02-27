package Playnet::Content::PAPER_EasterEggHeadline;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'38'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','38_content_select',q{select content from paper_stories where story_id = 21 limit 1});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $content = &doSelect('38_content_select','array_row');
	
	$content =~ s/<[^>]+>//g;
	
	if(length($content) < 400){
		
		if($vars->{'title_case'} eq 'Upper'){
			$vars->{'body'} = uc($vars->{'body'});
		}
		elsif($vars->{'title_case'} eq 'Random'){
			$vars->{'body'} = (&getRandomNumber(1,100) > 50) ? uc($vars->{'body'}): $vars->{'body'};
		}
		
		return 1;
	}
	
	return 0;

}

1;
