package Playnet::Content::PLAYER_RecentPromotion;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'37'} = \&handleContent;
	
	### add statements
	&addStatement('game_db','37_promotions_select',q{select pl.customerid,pl.callsign,pe.rankid,pe.branchid from wwii_player pl, wwii_persona pe where pl.modified > FROM_UNIXTIME(?) and pl.isplaynet != 1 and pl.playerid = pe.playerid and pe.rankid > 5 and pe.rankid < 14 and pe.rankpoints = 0 and pe.countryid = ? and pl.modified < pe.modified order by pe.rankid DESC limit 10});
	&addStatement('game_db','37_rank_select',q{select description from wwii_rank where rankid = ?});
	&addStatement('game_db','37_branch_select',q{select description from wwii_branch where branchid = ?});
	&addStatement('community_db','37_promotion_select',q{select count(*) from paper_promotions where customer_id = ? and rank_id = ?});
	&addStatement('community_db','37_promotion_insert',q{insert into paper_promotions (customer_id,rank_id,added) values (?,?,NOW())});
	
}

## PLAYER
## RANK
## BRANCH

sub handleContent(){
	
	my $vars = shift(@_);
	
	my $promotions = &doSelect('37_promotions_select','hashref_all', (time - 86400), $vars->{'country_id'});
	
	foreach my $promotion (@{$promotions}){
		
		my $storied = &doSelect('37_promotion_select','array_row', $promotion->{'customerid'}, $promotion->{'rankid'});
		
		if(!$storied){
			
			$vars->{'USER_ID'} 		= $promotion->{'customerid'};
			$vars->{'PLAYER'} 		= ucfirst(lc($promotion->{'callsign'}));
			$vars->{'RANK'} 		= &doSelect('37_rank_select','array_row', $promotion->{'rankid'});
			$vars->{'RANK_BRANCH'}	= &doSelect('37_branch_select','array_row', $promotion->{'branchid'});
			$vars->{'SIGNIFICANCE'}	= ($promotion->{'rankid'} > 8) ? 'high level': 'mid-level';
			
			&doUpdate('37_promotion_insert',$promotion->{'customerid'}, $promotion->{'rankid'});
			
			return 1;
		}
		
	}
	
	return 0;
	
}

1;
