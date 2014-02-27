#!/usr/bin/perl -w

## Standard Modules
use strict;
use Net::FTP;

use lib ('/usr/local/community/paper'); 

use Playnet::Database;
use Playnet::Email;
use Playnet::Misc;

BEGIN {
	
	## add the dbs here so that the content creation modules have access to them
	&addDatabase('community_db',"dbi:mysql:database=community;host=csr.wwiionline.com",'community','fun4all');
	&addDatabase('game_db',"dbi:mysql:database=wwiionline;host=gamedbu.wwiionline.com",'wwiiol','freebird');
	&addDatabase('map_db',"dbi:mysql:database=map;host=nagumo.playnet.com",'mapuser','drawpad');
	&addDatabase('auth_db',"dbi:mysql:database=auth;host=auth.playnet.com",'syncuser','mortin00');
	
	opendir(HANDLERS, "/usr/local/community/paper/Playnet/Content") || die "Can't open content directory: $!";
	
	foreach my $module (grep { /\.pm/ } readdir(HANDLERS))
	{
		$module =~ s/^(.+)\.pm$/$1/g;
		
		#print "Adding Content Module: $module\n";
		
		eval("use Playnet::Content::$module");
	}
	
	closedir(HANDLERS);
}

my %request		= &scriptInit();
my %common_vars	= &initCommonHandlers();
my %source_vars	= ();

#print "Story is $request{'story'}.\n";
#print "Source is $request{'source'}.\n";

&doUpdate('stories_repair');

my $stories = &doSelect('stories_select','hashref_all'); ## get all stories here expired or expire = true

foreach my $story (@{$stories})
{
	if(exists($request{'story'}) and $request{'story'} > 0 and $story->{'story_id'} != $request{'story'}){
		next;
	}
	
	print "Doing story ".$story->{'filename'}."\n";
	
	if(&generateStory($story))
	{
		
		print "Found type(".$story->{'TYPE_ID'}.")/source(".$story->{'SOURCE_ID'}.")/template(".$story->{'TEMPLATE_ID'}.")/vehicle(".$story->{'VEHICLE_ID'}.")\n";
		
		foreach my $svar (keys(%{$story})){
			
			last;
			print "Key is $svar\n";
			
			if($svar !~ /content/i and $svar !~ /template/i and $svar !~ /body/i and $svar !~ /alt/i){
				print "     $svar is ".$story->{$svar}."\n";
			}
			else{
				print "     $svar is TEXT\n";
			}
		}
		
		print "\n";
		
		if(&doUpdate('story_update',$story->{'CONTENT'},$story->{'TEMPLATE_ID'},$story->{'EXPIRES'},$story->{'STORY_ID'}))
		{
			open(INCLUDES,">/usr/local/community/paper/includes/".$story->{'FILENAME'}) or next;
			print INCLUDES $story->{'CONTENT'};
			close(INCLUDES);
		}
		
	}
	
	if(not -e "/usr/local/community/paper/includes/".$story->{'filename'} or -z "/usr/local/community/paper/includes/".$story->{'filename'}){
		system("/bin/echo \"&nbsp;\" > /usr/local/community/paper/includes/".$story->{'filename'});
	}
	
	if(0){ #-s "/usr/local/community/paper/includes/".$story->{'filename'}){
		
		#my $ftp = Net::FTP->new('marshall.playnet.com', Debug => 0);
		my $ftp2 = Net::FTP->new('app1.wwiionline.com', Debug => 0);
		
		#$ftp->login('rampage','mocajoe8');
		#$ftp->ascii();
		#$ftp->put("/usr/local/community/paper/includes/".$story->{'filename'},"/home/bv55/scriptinc/paper/".$story->{'filename'});
		#$ftp->quit();
		
		$ftp2->login('webuser','serveit');
		$ftp2->ascii();
		$ftp2->put("/usr/local/community/paper/includes/".$story->{'filename'},"/usr/local/tomcat/webapps/scripts/WEB-INF/includes/jsp/wwiionline/paper/".$story->{'filename'});
		$ftp2->quit();
		
		if(0){ # exists($story->{'TYPE_ID'}) and $story->{'TYPE_ID'} == 5){
			
			my $email = &doSelect('email_select','array_row',$story->{'USER_ID'});
			
			if(defined($email) and $email !~ /^$/){
				
				print "Emailing user: $email\n";
				
				my $template = &doSelect('email_template_select','array_row');
				
				if(defined($template) and $template !~ /^$/){
					&emailUser($email,'gazette@wwiionline.com','World@War Gazette Player Feature',&parse($template, $story));
				}
				
			}
			#else{
				#print "Email is not defined.\n";
			#}
			
		}
		
	}
	
}

print "The paper has been re-generated.\n";

&freeDatabases();

exit(0);

#######################################################################
############################# This Script's Init and Finish ###########
#######################################################################

sub scriptInit(){
	
	&addStatement('community_db','stories_repair',q{repair table paper_stories});
	&addStatement('community_db','stories_select',q{select * from paper_stories where expire = 'True' or NOW() > expires});
	&addStatement('community_db','rule_update',q{update paper_cycle_rules set storied = storied + 1 where rule_id = ? limit 1});
	&addStatement('community_db','archive_update',q{insert into paper_cycle_archives values (?,?,?,NOW())});
	&addStatement('community_db','story_update',q{update paper_stories set content = ?, used_id = ?, expire = 'False', expires = FROM_UNIXTIME(?) where story_id = ? limit 1});
	&addStatement('community_db','type_select',q{select t.*,c.country_id,c.side_id,c.name as country,c.side,c.adjective as country_adj from paper_stories s JOIN paper_story_types st JOIN paper_types t JOIN paper_story_countries sc JOIN paper_countries c where s.story_id = ? and s.story_id = st.story_id and st.type_id = t.type_id and s.story_id = sc.story_id and sc.country_id = c.country_id order by RAND(?)});
	#&addStatement('community_db','templates_select',q{SELECT s.source_id,s.weight,s.life,t.template_id,t.title,t.body,t.variety_1,t.variety_2,v.vehicle_id,v.name as vehicle,vcl.name as class,vct.name as category,b.name as branch FROM sources s, templates t,template_sources ts,template_countries tc,template_classes tcl,vehicles v,vehicle_classes vcl,vehicle_categories vct,branches b WHERE s.type_id = ? AND s.source_id = ts.source_id AND ts.template_id = t.template_id AND t.template_id = tc.template_id AND t.template_id = tcl.template_id AND tc.country_id = v.country_id AND tcl.class_id = v.class_id AND v.country_id = ? AND v.category_id = vct.category_id AND v.class_id = vcl.class_id AND v.branch_id = b.branch_id ORDER BY RAND()});
	&addStatement('community_db','templates_select',q{SELECT s.source_id,s.weight,s.life,t.template_id,t.title,t.body,t.variety_1,t.variety_2,t.duplicates,tc.country_id FROM paper_sources s JOIN paper_templates t JOIN paper_template_sources ts JOIN paper_template_countries tc WHERE s.type_id = ? AND s.source_id = ts.source_id AND ts.template_id = t.template_id AND t.template_id = tc.template_id AND tc.country_id = ? ORDER BY RAND(?)});
	&addStatement('community_db','vehicle_select',q{SELECT v.vehicle_id,v.name as vehicle,v.short_name as vehicle_short,vcl.name as class,vct.name as category,b.name as branch,tc.country_id,tcl.class_id FROM paper_templates t JOIN paper_template_countries tc JOIN paper_template_classes tcl JOIN paper_vehicles v JOIN paper_vehicle_classes vcl JOIN paper_vehicle_categories vct JOIN paper_branches b WHERE t.template_id = ? AND t.template_id = tc.template_id AND t.template_id = tcl.template_id AND tc.country_id = v.country_id AND v.country_id = ? AND tcl.class_id = v.class_id AND v.category_id = vct.category_id AND v.class_id = vcl.class_id AND v.branch_id = b.branch_id ORDER BY RAND(?) limit 1});
	#&addStatement('community_db','rdp_templates_select',q{SELECT s.source_id,s.weight,s.life,t.template_id,t.title,t.body,cy.cycle_id,cr.rule_id,cr.data,c.country_id,t.variety_1,t.variety_2,t.duplicates,v.vehicle_id,v.name as vehicle,v.short_name as vehicle_short,v.spawns,vcl.name as class,vct.name as category,b.name as branch FROM paper_story_countries sc,paper_countries c,paper_cycles cy,paper_cycle_rules cr,paper_sources s,paper_template_sources ts,paper_template_countries tc,paper_template_classes tcl,paper_templates t,paper_vehicles v,paper_vehicle_classes vcl,paper_vehicle_categories vct,paper_branches b WHERE sc.story_id = ? AND sc.country_id = c.country_id AND c.cycle_id = cy.cycle_id AND cy.cycle_id = cr.cycle_id AND cr.source_id = s.source_id AND s.weight <= ((cy.produced / cy.goal) * 100) AND s.source_id = ts.source_id AND ts.template_id = t.template_id AND t.template_id = tc.template_id AND tc.country_id = c.country_id AND t.template_id = tcl.template_id AND tcl.class_id = v.class_id AND v.vehicle_id = cr.vehicle_id AND v.category_id = vct.category_id AND v.class_id = vcl.class_id AND v.branch_id = b.branch_id ORDER BY RAND(?)});
	&addStatement('community_db','rdp_templates_select',q{
		SELECT DISTINCT s.source_id,
			s.weight,
			s.life,
			t.template_id,
			t.title,
			t.body,
			cy.cycle_id,
			cy.rdp_cycle,
			c.country_id,
			t.variety_1,
			t.variety_2,
			t.duplicates
		FROM paper_story_countries sc
			JOIN paper_countries c
			JOIN paper_cycles cy
			JOIN paper_story_types st
			JOIN paper_sources s
			JOIN paper_template_sources ts
			JOIN paper_template_countries tc
			JOIN paper_template_classes tcl
			JOIN paper_templates t
		WHERE sc.story_id = ?
			AND sc.country_id = c.country_id
			AND c.cycle_id = cy.cycle_id
			AND st.story_id = sc.story_id
			AND s.type_id = st.type_id
			AND s.source_id = ts.source_id
			AND ts.template_id = t.template_id
			AND t.template_id = tc.template_id
			AND tc.country_id = c.country_id
			AND t.template_id = tcl.template_id
		ORDER BY RAND(?)
	});
	&addStatement('community_db','template_vehicle_select',q{
		SELECT v.vehicle_id,
			v.name as vehicle,
			v.short_name as vehicle_short,
			vct.name as category,
			vcl.name as class,
			b.name as branch
		FROM paper_vehicles v
			JOIN paper_vehicle_categories vct
			JOIN paper_vehicle_classes vcl
			JOIN paper_branches b
			JOIN paper_templates t
			JOIN paper_template_classes tcl
		WHERE v.country_id = ?
			AND v.category_id = ?
			AND v.class_id = ?
			AND v.type_id = ?
			AND vct.category_id = v.category_id
			AND vcl.class_id = v.class_id
			AND b.branch_id = v.branch_id
			AND t.template_id = ?
			AND tcl.template_id = t.template_id
			AND tcl.class_id = v.class_id
		LIMIT 1
	});
	&addStatement('community_db','used_select',q{SELECT count(*) FROM paper_stories WHERE page_id = ? and story_id != ? and used_id = ?});
	&addStatement('community_db','email_template_select',q{select body from paper_templates where template_id = 57 limit 1});
	&addStatement('auth_db','email_select',q{select email from auth_customer where customerid = ? limit 1});
	&addStatement('game_db','rdp_rules_select',q{SELECT DISTINCT * FROM strat.v_hcunits_rdp_in_progress WHERE country_id = ? AND action = ? ORDER BY RAND()});
	
	my %params = ();
	
	if(defined($ARGV[0]) and $ARGV[0] !~ /^$/){
		$params{'story'} = $ARGV[0];
	}
	
	if(defined($ARGV[1]) and $ARGV[1] !~ /^$/){
		$params{'source'} = $ARGV[1];
	}
	
	return %params;
}

sub generateStory(){
	
	my $story = shift(@_);
	
	### randomly select a type to use restricted by country
	my $types = &doSelect('type_select','hashref_all',$story->{'story_id'},&getRandomNumber(1,1000));
	
	foreach my $type (@{$types}){
		
		&hashMerge($story, $type);
		
		my $templates = undef;
		
		if($story->{'weighting'} eq 'RDP'){
			$templates = &doSelect('rdp_templates_select','hashref_all',$story->{'story_id'},&getRandomNumber(1,1000));
		}
		else{
			$templates = &doSelect('templates_select','hashref_all',$story->{'type_id'},$story->{'country_id'},&getRandomNumber(1,1000));
		}
		
		my $organized	= &organizeTemplates($templates);
		my @weights		= ($story->{'weighting'} eq 'Absolute') ? reverse(sort(keys(%{$organized}))): &orderWeights(keys(%{$organized}));
		
		foreach my $weight (@weights){
			
			foreach my $template (@{$organized->{$weight}}){
				
				if(exists($request{'source'}) and $request{'source'} > 0 and $template->{'source_id'} != $request{'source'}){
					next;
				}
				
				if($template->{'duplicates'} eq 'Disallow'){
					
					my $used = &doSelect('used_select','array_row',$story->{'page_id'},$story->{'story_id'},$template->{'template_id'});
					
					if($used > 0){
						next;
					}
					
				}
				
				if(!exists($template->{'vehicle_id'})){
					
					#print "Vehicle ID not found, adding...\n";
					
					my $vehicle = &doSelect('vehicle_select','hashref_row',$template->{'template_id'},$template->{'country_id'},&getRandomNumber(1,1000));
					
					&hashMerge($template, $vehicle);
					
					#print "Vehicle ID is now ".$template->{'vehicle_id'}."\n";
					
				}
				
				&hashMerge($story, $template);
				
				#print "Processing Weight: weight($weight)/source(".$template->{'source_id'}.")/template(".$template->{'template_id'}.")/country(".$template->{'country_id'}.")\n";
				
				if(exists($::source_vars{$story->{'source_id'}}) and $::source_vars{$story->{'source_id'}}->($story) == 1)
				{
					print "Found Handler for Weight: weight($weight)/source(".$template->{'source_id'}.")/template(".$template->{'template_id'}.")/country(".$template->{'country_id'}.")\n";
					#print "Handler found!\n";
					
					foreach my $svar (keys(%{$story}))
					{
						if(!exists($story->{uc($svar)}))
						{
							$story->{uc($svar)} = $story->{$svar};
						}
					}
					
					$story->{'TITLE'} 		= &parse($story->{'TITLE'}, $story);
					$story->{'BODY'} 		= &parse($story->{'BODY'}, $story);
					
					#if($story->{'WEIGHTING'} eq 'RDP'){
					#	&doUpdate('rule_update',$story->{'rule_id'});
					#	&doUpdate('archive_update',$story->{'CYCLE_ID'},$story->{'RULE_ID'},$story->{'TITLE'});
					#}
					
					if($story->{'TITLE_CASE'} eq 'Upper'){
						$story->{'TITLE'} = uc($story->{'TITLE'});
					}
					elsif($story->{'TITLE_CASE'} eq 'Random'){
						$story->{'TITLE'} = (&getRandomNumber(1,100) > 50) ? uc($story->{'TITLE'}): $story->{'TITLE'};
					}
					
					if(&validSize($story))
					{
						$story->{'CONTENT'} 	= &parse($story->{'TEMPLATE'}, $story);
						$story->{'EXPIRES'}		= time + ($story->{'LIFE'} * 60);
						
						return 1;
					}
				}
			}
			
		}
		
	}
	
	if(defined($story->{'alt'}) and $story->{'alt'} =~ /\w/){
		
		print "### Using Default Story for ".$story->{'filename'}." ###\n";
		
		foreach my $svar (keys(%{$story})){
			$story->{uc($svar)} = $story->{$svar};
		}
		
		$story->{'CONTENT'} 	= $story->{'alt'};
		$story->{'EXPIRES'}		= time + 3600;
		
		return 1;
	}
	
	return 0;
}

sub orderWeights(){
	
	my @weights = @_;
	my %sorted	= ();
	
	foreach my $weight (@weights){
		
		my $random = &getRandomNumber(1,100);
		
		while($random > $weight){
			$random = &getRandomNumber(1,100);
		}
		
		$sorted{$weight} = $random;
		
	}
	
	return sort {$sorted{$b} <=> $sorted{$a}} (keys(%sorted));
}

sub parse(){
	
	my $template 	= shift(@_);
	my $variables	= shift(@_);
	
	while($template =~ /%(\w+)%/s){
		
		my $variable = uc($1);
		
		print "Parsing variable: $variable\n";
		
		if(exists($variables->{$variable})){
			$template =~ s/%$variable%/$variables->{$variable}/gse;
		}
		elsif(exists($common_vars{$variable})){
			$template =~ s/%$variable%/$common_vars{$variable}->($variables)/gse;
		}
		else{
			$template =~ s/%$variable%//gs;
		}
		
	}
	
	return $template;
}

sub organizeTemplates(){
	
	my $templates 	= shift(@_);
	my %organizer	= ();
	
	if(defined($templates)){
		
		foreach my $template (@{$templates}){
			
			if(!exists($organizer{$template->{'weight'}})){
				$organizer{$template->{'weight'}} = [];
			}
			
			push(@{$organizer{$template->{'weight'}}}, $template);
			
		}
		
	}
	
	return \%organizer;
}

sub validSize(){
	
	my $story = shift(@_);
	
	if($story->{'SIZE'} > 0){
		
		my $title 	= $story->{'TITLE'};
		my $body	= $story->{'BODY'};
		
		$title 	=~ s/<[^>]+>//g;
		$body 	=~ s/<[^>]+>//g;
		
		if(((length($title) * 2) + length($body)) > $story->{'SIZE'}){
			return 0;
		}
		
	}
	
	return 1;
	
}
