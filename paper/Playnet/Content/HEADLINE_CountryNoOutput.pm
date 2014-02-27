package Playnet::Content::HEADLINE_CountryNoOutput;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'27'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','27_producing_select',q{select count(*) from paper_factories where ocountry_id = ? and status in ('Full Production','Reduced Production')});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $producing = &doSelect('27_producing_select','array_row',$vars->{'country_id'});
	
	if($producing == 0){
		return 1;
	}
	
	return 0;


}

1;
