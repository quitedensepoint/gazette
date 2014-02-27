package Playnet::Adversaries;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/paper'); 
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(updateAdversaries);

my $inited 		= 0;
my %adversaries = ();

sub updateAdversaries(){
	
	my $sortie = shift(@_);
	
	if($sortie->{'kill_count'} > 0 and defined($sortie->{'kills'})){
		
		foreach my $k (@{$sortie->{'kills'}}){
			
			if(exists($k->{'used_kill'})){
				
				if(!exists($personas{$sortie->{'persona_id'}}->{'adversaries'}->{$k->{'opponent_persona_id'}})){
					$personas{$sortie->{'persona_id'}}->{'adversaries'}->{$k->{'opponent_persona_id'}} = 0;
				}
				
				$personas{$sortie->{'persona_id'}}->{'adversaries'}->{$k->{'opponent_persona_id'}} += 1;
				
			}
			
		}
		
	}
	
}

sub processAdversaries(){
	
	my $sorties = shift(@_);
	
	if(&initQueries()){
	
	}
	
}

sub initQueries(){
	
	if(!$inited){
		
		## Adversaries
		&addStatement('community_db','adversary_insert',q{
			INSERT INTO scoring_persona_adversaries (persona_id, opponent_persona_id, member_id, opponent_member_id, kills, added, modified)
			VALUES (?,?,?,?,?,NOW(),NOW())
		});
		
		&addStatement('community_db','adversary_update',q{
			UPDATE scoring_persona_adversaries
			SET kills = kills + ?, modified = NOW()
			WHERE persona_id = ? AND opponent_persona_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','campaign_adversary_insert',q{
			INSERT INTO scoring_campaign_adversaries (persona_id, opponent_persona_id, member_id, opponent_member_id, kills, added, modified)
			VALUES (?,?,?,?,?,NOW(),NOW())
		});
		
		&addStatement('community_db','campaign_adversary_update',q{
			UPDATE scoring_campaign_adversaries
			SET kills = kills + ?, modified = NOW()
			WHERE persona_id = ? AND opponent_persona_id = ?
			LIMIT 1
		});
		
		$inited = 1;
		
	}
}

1;
