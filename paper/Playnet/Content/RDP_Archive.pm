package Playnet::Content::RDP_Archive;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'14'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','14_archive_select',q{select a.* from paper_countries c, paper_cycle_archives a where c.side_id = ? and c.cycle_id = a.cycle_id order by a.added DESC limit 17});

}

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $headlines 	= &doSelect('14_archive_select','hashref_all',$vars->{'side_id'});
	
	if(defined($headlines) and defined($headlines->[0]->{'title'})){
		
		my $body = '';
		
		foreach my $headline (@{$headlines}){
			$body .= &miniParser($vars->{'body'}, $headline);
		}
		
		$vars->{'body'} = $body;
		
		return 1;
		
	}
	
	return 0;
	
}

1;
