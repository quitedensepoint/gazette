package Playnet::Awards;

use strict;
use DBI;
use Digest::MD5 qw(md5_hex);

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(getRankReq getAwardReq getSortieReq getSummaryReq getVersusClassReq getVersusCategoryReq);

my $inited = 0;

sub getRankReq(){
	
	my $persona	= shift(@_);
	my $award 	= shift(@_);
	my $rule	= shift(@_);
	
	#print "               Processing Rank Req: ".$persona->{'rank_id'}." ".$rule->{'operator'}." ".$rule->{'value'}."\n";
	
	return &processResult($persona->{'rank_id'}, $rule->{'operator'}, $rule->{'value'});
	
}

sub getAwardReq(){
	
	my $persona	= shift(@_);
	my $award 	= shift(@_);
	my $rule	= shift(@_);
	
	&initQueries();
	
	#print "               Processing Award Req: ".$rule->{'field'}." ".$rule->{'operator'}." ".$rule->{'value'}."\n";
	
	my $result = ($award->{'scope'} eq 'Campaign') ? &doSelect('campaign_award_select', 'array_row', $persona->{'persona_id'}, $rule->{'value'}, $award->{'current_campaign_id'}): &doSelect('career_award_select', 'array_row', $persona->{'persona_id'}, $rule->{'value'});
	
	return &processResult($result, (($rule->{'operator'} eq '=') ? '>': '=='), 0);
	
}

sub getSortieReq(){
	
	my $persona	= shift(@_);
	my $award 	= shift(@_);
	my $rule	= shift(@_);
	
	my $query = '';
	my $where = '';
	
	foreach my $requirement (@{$rule->{'requirements'}}){
		
		#print "               Adding Where Requirement: ".$requirement->{'field'}."\n";
		
		$where .= 'AND scs.'.$requirement->{'field'}.' '.$requirement->{'operator'}." ".$requirement->{'value'}." ";
	}
	
	if($award->{'vehicle_id'} > 0){
		$query = "SELECT count(*) FROM scoring_campaign_sorties scs WHERE scs.persona_id = ".$persona->{'persona_id'}." ".$where." AND scs.vehicle_id = ".$award->{'vehicle_id'};
	}
	elsif($award->{'class_id'} > 0){
		$query = "SELECT count(*) FROM scoring_campaign_sorties scs, scoring_vehicles sv WHERE scs.persona_id = ".$persona->{'persona_id'}." ".$where." AND scs.vehicle_id = sv.vehicle_id AND sv.class_id = ".$award->{'class_id'};
	}
	else{
		$query = "SELECT count(*) FROM scoring_campaign_sorties scs WHERE scs.persona_id = ".$persona->{'persona_id'}." ".$where;
	}
	
	#print "               Processing Sortie Req: ".$query."\n";
	
	my $result = &doQuery('community_db', &md5_hex($query), $query, 'array_row');
	
	return &processResult($result, $rule->{'operator'}, $rule->{'sorties'});
	
}

sub getSummaryReq(){
	
	my $persona	= shift(@_);
	my $award 	= shift(@_);
	my $rule	= shift(@_);
	my $scope	= shift(@_);
	
	my $query	= '';
	my $table	= ($rule->{'field'} eq 'tom' or $rule->{'field'} eq 'spawns') ? 'scoring_'.$scope.'_spawns': 'scoring_'.$scope.'_versus';
	
	if($award->{'vehicle_id'} > 0){
		$query = "SELECT ".$rule->{'accumulator'}."(".$rule->{'field'}.") as result FROM $table WHERE persona_id = ".$persona->{'persona_id'}." AND vehicle_id = ".$award->{'vehicle_id'};
	}
	elsif($award->{'class_id'} > 0){
		$query = "SELECT ".$rule->{'accumulator'}."(sc.".$rule->{'field'}.") as result FROM $table sc, scoring_vehicles sv WHERE sc.persona_id = ".$persona->{'persona_id'}." AND sc.vehicle_id = sv.vehicle_id AND sv.class_id = ".$award->{'class_id'};
	}
	else{
		$query = "SELECT ".$rule->{'accumulator'}."(".$rule->{'field'}.") as result FROM scoring_".$scope."_personas WHERE persona_id = ".$persona->{'persona_id'};
	}
	
	#print "               Processing ".ucfirst($scope)." Summary Req: ".$query."\n";
	
	my $result = &doQuery('community_db', &md5_hex($query), $query, 'array_row');
	
	return &processResult($result, $rule->{'operator'}, $rule->{'value'});
	
}

sub getVersusClassReq(){
	
	my $persona	= shift(@_);
	my $award 	= shift(@_);
	my $rule	= shift(@_);
	my $scope	= shift(@_);
	
	my $query	= '';
	my $table	= ($scope eq 'campaign') ? 'scoring_campaign_versus': 'scoring_career_versus';
	
	if($award->{'vehicle_id'} > 0){
		$query = "SELECT ".$rule->{'accumulator'}."(scv.".$rule->{'field'}.") as result FROM $table scv, scoring_vehicles o WHERE scv.persona_id = ".$persona->{'persona_id'}." AND scv.vehicle_id = ".$award->{'vehicle_id'}." AND scv.opponent_vehicle_id = o.vehicle_id AND o.class_id = ".$rule->{'extra'};
	}
	elsif($award->{'class_id'} > 0){
		$query = "SELECT ".$rule->{'accumulator'}."(scv.".$rule->{'field'}.") as result FROM $table scv, scoring_vehicles v, scoring_vehicles o WHERE scv.persona_id = ".$persona->{'persona_id'}." AND scv.vehicle_id = v.vehicle_id AND v.class_id = ".$award->{'class_id'}." AND scv.opponent_vehicle_id = o.vehicle_id AND o.class_id = ".$rule->{'extra'};
	}
	else{
		$query = "SELECT ".$rule->{'accumulator'}."(scv.".$rule->{'field'}.") as result FROM $table scv, scoring_vehicles o WHERE scv.persona_id = ".$persona->{'persona_id'}." AND scv.opponent_vehicle_id = o.vehicle_id AND o.class_id = ".$rule->{'extra'};
	}
	
	#print "               Processing ".ucfirst($scope)." Versus Class Req: ".$query."\n";
	
	my $result = &doQuery('community_db', &md5_hex($query), $query, 'array_row');
	
	return &processResult($result, $rule->{'operator'}, $rule->{'value'});
	
}

sub getVersusCategoryReq(){
	
	my $persona	= shift(@_);
	my $award 	= shift(@_);
	my $rule	= shift(@_);
	my $scope	= shift(@_);
	
	my $query	= '';
	my $table	= ($scope eq 'campaign') ? 'scoring_campaign_versus': 'scoring_career_versus';
	
	if($award->{'vehicle_id'} > 0){
		$query = "SELECT ".$rule->{'accumulator'}."(scv.".$rule->{'field'}.") as result FROM $table scv, scoring_vehicles o WHERE scv.persona_id = ".$persona->{'persona_id'}." AND scv.vehicle_id = ".$award->{'vehicle_id'}." AND scv.opponent_vehicle_id = o.vehicle_id AND o.category_id = ".$rule->{'extra'};
	}
	elsif($award->{'class_id'} > 0){
		$query = "SELECT ".$rule->{'accumulator'}."(scv.".$rule->{'field'}.") as result FROM $table scv, scoring_vehicles v, scoring_vehicles o WHERE scv.persona_id = ".$persona->{'persona_id'}." AND scv.vehicle_id = v.vehicle_id AND v.class_id = ".$award->{'class_id'}." AND scv.opponent_vehicle_id = o.vehicle_id AND o.category_id = ".$rule->{'extra'};
	}
	else{
		$query = "SELECT ".$rule->{'accumulator'}."(scv.".$rule->{'field'}.") as result FROM $table scv, scoring_vehicles o WHERE scv.persona_id = ".$persona->{'persona_id'}." AND scv.opponent_vehicle_id = o.vehicle_id AND o.category_id = ".$rule->{'extra'};
	}
	
	#print "               Processing ".ucfirst($scope)." Versus Category Req: ".$query."\n";
	
	my $result = &doQuery('community_db', &md5_hex($query), $query, 'array_row');
	
	return &processResult($result, $rule->{'operator'}, $rule->{'value'});
	
}

sub initQueries(){
	
	### Campaign Requirements
	
	if(!$inited){
		
		&addStatement('community_db','career_award_select',q{
			SELECT count(*) FROM scoring_career_awards WHERE persona_id = ? AND award_id = ?
		});
		
		&addStatement('community_db','campaign_award_select',q{
			SELECT count(*) FROM scoring_career_awards WHERE persona_id = ? AND award_id = ? AND campaign_id = ?
		});
		
		$inited = 1;
		
	}
}

sub processResult(){
	
	my $result 		= shift(@_);
	my $operator 	= shift(@_);
	my $value 		= shift(@_);
	
	$operator = ($operator eq '=') ? '==': $operator;
	
	my $r = (defined($result) and defined($operator) and defined($value)) ? eval("($result $operator $value) ? 1: 0"): 0;
	
	#print "                    Result is : ".(($r == 1) ? 'PASS': 'FAIL')."\n";
	
	return $r;

}

1;
