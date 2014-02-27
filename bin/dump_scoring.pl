#!/usr/bin/perl -w

## Standard Modules
use strict;

chomp(@ARGV);

my $campaign = $ARGV[0];

if(!$campaign or $campaign !~ /\d+/)
{
  print "You must specify a campaign id to use for the dump file name!\n";
  exit;
}

my $cid = int($campaign);

#print "Dumping test tables ...\n";
#&dump_tables("test_scoring_$cid", ["scoring_activity_campaign", "scoring_activity_hourly"]);

print "Dumping campaign tables ...\n";
&dump_tables("campaign_scoring_$cid", ["scoring_activity_campaign", "scoring_activity_hourly", "scoring_award_rulesets", "scoring_award_types", "scoring_awards", "scoring_branches", "scoring_campaign_aars", "scoring_campaign_activity", "scoring_campaign_adversaries", "scoring_campaign_awards", "scoring_campaign_captures", "scoring_campaign_countries", "scoring_campaign_crews", "scoring_campaign_kills", "scoring_campaign_mcversus", "scoring_campaign_personas", "scoring_campaign_sorties", "scoring_campaign_spawns", "scoring_campaign_streak_bests", "scoring_campaign_streaks", "scoring_campaign_versus", "scoring_campaigns", "scoring_countries", "scoring_journal_pages", "scoring_journal_types", "scoring_journals", "scoring_mission_codes", "scoring_persona_configs", "scoring_player_history", "scoring_ranks", "scoring_rtb_codes", "scoring_rule_types", "scoring_rules", "scoring_rulesets", "scoring_sides", "scoring_streaks", "scoring_top_personas", "scoring_top_streaks", "scoring_tops", "scoring_vehicle_categories", "scoring_vehicle_classes", "scoring_vehicle_spawns_campaign", "scoring_vehicle_spawns_history", "scoring_vehicle_translations", "scoring_vehicle_versus_campaign", "scoring_vehicle_versus_history", "scoring_vehicle_xcategories", "scoring_vehicle_xclasses", "scoring_vehicles"]);

print "Dumping career tables ...\n";
&dump_tables("career2_scoring_$cid", ["scoring_career_awards", "scoring_career_campaigns", "scoring_career_history", "scoring_career_mcversus", "scoring_career_personas", "scoring_career_spawns", "scoring_career_streaks", "scoring_career_versus"]);

print "Dumping the career adversary table, this could take a while!\n";
&dump_tables("career1_scoring_$cid", ["scoring_career_adversaries"]);

print "Done.\n";

exit(0);

sub dump_tables($$)
{
  my $filename = shift;
  my $tables = shift;
  my $list = join(" ", @{$tables});
  my $cmd = "/usr/bin/mysqldump --opt -v -u root --password=gravytrain community $list | gzip > /usr/local/community/dumps/$filename.sql.gz";
  
  # run the command
  `$cmd`;
}
