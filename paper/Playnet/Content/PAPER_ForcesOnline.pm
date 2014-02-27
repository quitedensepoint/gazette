package Playnet::Content::PAPER_ForcesOnline;

use strict;

use lib ('/usr/local/community/paper'); 
use Playnet::Database;
use Playnet::Misc;

INIT {
	
	### add handler
	$main::source_vars{'45'} = \&handleContent;
	
	### add statements
	&addStatement('community_db','45_sorties_select',q{
		SELECT pl.customerid as member_id,
			v.category_id,
			c.side_id
		FROM scoring_campaign_sorties s,
			wwii_persona pe,
			wwii_player pl,
			scoring_vehicles v,
			scoring_countries c
		WHERE s.persona_id = pe.personaid
			AND pe.playerid = pl.playerid
			AND s.vehicle_id = v.vehicle_id
			AND v.country_id = c.country_id
		ORDER BY s.added DESC
		LIMIT 1000
	});
	
}

sub handleContent(){
	
	my $vars 		= shift(@_);
	
	my %players		= ();
	my $cutoff 		= time - 1000;
	my $sorties 	= &doSelect('45_sorties_select','hashref_all');
	
	print "Processing sorties from $cutoff ...\n";
	
	foreach my $sortie (@{$sorties}){
		
		if(!exists($players{$sortie->{'member_id'}})){
			
			print "     Adding new player: ".$sortie->{'member_id'}."\n";
			
			$players{$sortie->{'member_id'}} = {'1' => {}, '2' => {}};
			
			$players{$sortie->{'member_id'}}->{'1'}->{'1'} 	= 0; # air
			$players{$sortie->{'member_id'}}->{'1'}->{'2'} 	= 0; # armour
			$players{$sortie->{'member_id'}}->{'1'}->{'3'} 	= 0; # navy
			$players{$sortie->{'member_id'}}->{'1'}->{'4'} 	= 0; # infantry
			
			$players{$sortie->{'member_id'}}->{'2'}->{'1'} 	= 0;
			$players{$sortie->{'member_id'}}->{'2'}->{'2'} 	= 0;
			$players{$sortie->{'member_id'}}->{'2'}->{'3'} 	= 0;
			$players{$sortie->{'member_id'}}->{'2'}->{'4'} 	= 0;
			
		}
		
		$players{$sortie->{'member_id'}}->{$sortie->{'side_id'}}->{$sortie->{'category_id'}} += 1;
		
	}
	
	#sleep(10);
	
	my %spawns = ('1' => {}, '2' => {});
	
	$spawns{'1'}->{'1'} 	= 0;
	$spawns{'1'}->{'2'} 	= 0;
	$spawns{'1'}->{'3'} 	= 0;
	$spawns{'1'}->{'4'} 	= 0;
	
	$spawns{'2'}->{'1'} 	= 0;
	$spawns{'2'}->{'2'} 	= 0;
	$spawns{'2'}->{'3'} 	= 0;
	$spawns{'2'}->{'4'} 	= 0;
	
	print "Collecting spawns count...\n";
	
	foreach my $mid (keys(%players)){
		
		print "     Find best vehicle for ".$mid."\n";
		
		my @allied_sorted 	= sort {$players{$mid}->{'1'}->{$b} <=> $players{$mid}->{'1'}->{$a}} keys(%{$players{$mid}->{'1'}});
		my @axis_sorted 	= sort {$players{$mid}->{'2'}->{$b} <=> $players{$mid}->{'2'}->{$a}} keys(%{$players{$mid}->{'2'}});
		
		my $allied 	= $allied_sorted[0];
		my $axis 	= $axis_sorted[0];
		
		my $allied_list = '';
		my $axis_list 	= '';
		
		foreach my $as (@allied_sorted){
			$allied_list .= $as.':'.$players{$mid}->{'1'}->{$as}.'/';
		}
		
		foreach my $as (@axis_sorted){
			$axis_list .= $as.':'.$players{$mid}->{'2'}->{$as}.'/';
		}
		
		chop($allied_list);
		chop($axis_list);
		
		print "     Allied: $allied (".$players{$mid}->{'1'}->{$allied}." $allied_list) / Axis: $axis (".$players{$mid}->{'2'}->{$axis}." $axis_list): ";
		
		if($players{$mid}->{'1'}->{$allied} == $players{$mid}->{'2'}->{$axis}){
			
			my $random_winner = &getRandomWinner(1,2);
			
			if($random_winner == 1){
				$spawns{'1'}->{$allied} += 1;
			}
			else{
				$spawns{'2'}->{$axis} += 1;
			}
			
			print "Random Winner!!!\n";
		}
		elsif($players{$mid}->{'1'}->{$allied} > $players{$mid}->{'2'}->{$axis}){
			$spawns{'1'}->{$allied} += 1;
			print "Allies Win!!!\n";
		}
		else{
			$spawns{'2'}->{$axis} 	+= 1;
			print "Axis Win!!!\n";
		}
		
		#sleep(1);
		
	}
	
	my $icon_count		= 6;
	my %icons = ('1' => {}, '2' => {});
	
	$icons{'1'}->{'1'} 	= 0;
	$icons{'1'}->{'2'} 	= 0;
	$icons{'1'}->{'3'} 	= 0;
	$icons{'1'}->{'4'} 	= 0;
	
	$icons{'2'}->{'1'} 	= 0;
	$icons{'2'}->{'2'} 	= 0;
	$icons{'2'}->{'3'} 	= 0;
	$icons{'2'}->{'4'} 	= 0;
	
	print "Collecting icon counts...\n";
	
	for(my $i = 0; $i < 4; $i++){
		
		my $id = $i + 1;
		
		if($spawns{'1'}->{$id} > $spawns{'2'}->{$id}){
			# allies
			$icons{'1'}->{$id} = $icon_count;
			$icons{'2'}->{$id} = ($spawns{'1'}->{$id} > 0) ? &round(($spawns{'2'}->{$id} / $spawns{'1'}->{$id}) * $icon_count): 0;
		}
		else{
			# axis
			$icons{'2'}->{$id} = $icon_count;
			$icons{'1'}->{$id} = ($spawns{'2'}->{$id} > 0) ? &round(($spawns{'1'}->{$id} / $spawns{'2'}->{$id}) * $icon_count): 0;
		}
		
		print "$id: ".$icons{'1'}->{$id}."/".$icons{'2'}->{$id}."\n";
		
	}
	
	print "Initing variables (icons: $icon_count).\n";
	
	print "     Allied Infantry: ".$icons{'1'}->{'4'}."\n";
	$vars->{'ALLIED_INFANTRY'} 	= &createIcon('', $icons{'1'}->{'4'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/infantry_allied.gif" width="16" height="16">');
	$vars->{'ALLIED_INFANTRY'} 	= &createIcon($vars->{'ALLIED_INFANTRY'}, ($icon_count - $icons{'1'}->{'4'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/infantry_blank.gif" width="16" height="16">');
	
	print "     Axis Infantry: ".$icons{'2'}->{'4'}."\n";
	$vars->{'AXIS_INFANTRY'} 	= &createIcon('', ($icon_count - $icons{'2'}->{'4'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/infantry_blank.gif" width="16" height="16">');
	$vars->{'AXIS_INFANTRY'} 	= &createIcon($vars->{'AXIS_INFANTRY'}, $icons{'2'}->{'4'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/infantry_axis.gif" width="16" height="16">');
	
	print "     Allied Armour: ".$icons{'1'}->{'2'}."\n";
	$vars->{'ALLIED_ARMOUR'} 	= &createIcon('', $icons{'1'}->{'2'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/armour_allied.gif" width="16" height="16">');
	$vars->{'ALLIED_ARMOUR'} 	= &createIcon($vars->{'ALLIED_ARMOUR'}, ($icon_count - $icons{'1'}->{'2'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/armour_blank.gif" width="16" height="16">');
	
	print "     Axis Armour: ".$icons{'2'}->{'2'}."\n";
	$vars->{'AXIS_ARMOUR'} 		= &createIcon('', ($icon_count - $icons{'2'}->{'2'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/armour_blank.gif" width="16" height="16">');
	$vars->{'AXIS_ARMOUR'} 		= &createIcon($vars->{'AXIS_ARMOUR'}, $icons{'2'}->{'2'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/armour_axis.gif" width="16" height="16">');
	
	print "     Allied Air: ".$icons{'1'}->{'1'}."\n";
	$vars->{'ALLIED_AIR'} 		= &createIcon('', $icons{'1'}->{'1'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/air_allied.gif" width="16" height="16">');
	$vars->{'ALLIED_AIR'} 		= &createIcon($vars->{'ALLIED_AIR'}, ($icon_count - $icons{'1'}->{'1'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/air_blank.gif" width="16" height="16">');
	
	print "     Axis Air: ".$icons{'2'}->{'1'}."\n";
	$vars->{'AXIS_AIR'} 		= &createIcon('', ($icon_count - $icons{'2'}->{'1'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/air_blank.gif" width="16" height="16">');
	$vars->{'AXIS_AIR'} 		= &createIcon($vars->{'AXIS_AIR'}, $icons{'2'}->{'1'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/air_axis.gif" width="16" height="16">');
	
	print "     Allied Navy: ".$icons{'1'}->{'3'}."\n";
	$vars->{'ALLIED_NAVY'} 		= &createIcon('', $icons{'1'}->{'3'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/navy_allied.gif" width="16" height="16">');
	$vars->{'ALLIED_NAVY'} 		= &createIcon($vars->{'ALLIED_NAVY'}, ($icon_count - $icons{'1'}->{'3'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/navy_blank.gif" width="16" height="16">');
	
	print "     Axis Navy: ".$icons{'2'}->{'3'}."\n";
	$vars->{'AXIS_NAVY'} 		= &createIcon('', ($icon_count - $icons{'2'}->{'3'}), '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/navy_blank.gif" width="16" height="16">');
	$vars->{'AXIS_NAVY'} 		= &createIcon($vars->{'AXIS_NAVY'}, $icons{'2'}->{'3'}, '<img src="http://www.mirror.wwiionline.com/images/gazettenew/forces/navy_axis.gif" width="16" height="16">');
	
	return 1;

}

sub round(){
	return int(shift(@_) + 0.5);
}

sub createIcon(){
	
	my $str = shift(@_);
	my $cnt = shift(@_);
	my $icn = shift(@_);
	
	print "Icon count is: $cnt\n";
	
	for(my $c = 0; $c < $cnt; $c++){
		$str .= $icn;
	}
	
	return $str;
}

sub getRandomWinner($$) {
	
	my($min, $max) = @_;
	
    # Assumes that the two arguments are integers themselves!
	
    return $min if $min == $max;
	
    ($min, $max) = ($max, $min)  if  $min > $max;
	
    return $min + int rand(1 + $max - $min);
	
}  

1;
