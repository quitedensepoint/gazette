package Playnet::Config;

use strict;
use Config::IniFiles;
use lib ('/usr/local/community/events');
use Playnet::Debug;

require Exporter;

use vars qw(@ISA @EXPORT);

@ISA 			= qw(Exporter);
@EXPORT 		= qw(initConfig loadConfigSection getConfigGroupMembers mergeConfigSections getOrderedSectionMembers);

my %configs		= ();

sub initConfig(){
	my $config_name = uc(shift(@_));
	my $config_file = shift(@_);
	
	if(!exists($configs{$config_name})){
		my $config	= (-e $config_file) ? new Config::IniFiles(-file => $config_file): die "Unable to load config file $config_file.";
		
		if(!defined($config)){
			&debug('DEBUG', 'ERROR', "Config $config_name is not defined.");
		}
		else{
			$configs{$config_name} = $config;
		}
	}
	else{
		&debug('DEBUG', 'INFO', "Config $config_name already exists.");
	}
}

sub loadConfigSection(){
	my $config_name		= uc(shift(@_));
	my $section_name	= uc(shift(@_));
	my %section_data	= @_;
	
	if(!%section_data){
		%section_data	= ();
	}
	
	if(exists($configs{$config_name})){
		foreach my $d ($configs{$config_name}->Parameters($section_name)){
			$section_data{uc($d)}	= $configs{$config_name}->val($section_name,$d);
		}
	}
	else{
		&debug('DEBUG', 'WARNING', "Config $config_name not found.");
	}
	
	return %section_data;
}

sub getConfigGroupMembers(){
	my $config_name		= uc(shift(@_));
	my $group_name		= uc(shift(@_));
	
	my @group_members	= ();
	
	if(exists($configs{$config_name})){
		@group_members = $configs{$config_name}->GroupMembers($group_name);
	}
	
	return @group_members;
}

sub mergeConfigSections(){
	my $source 		= shift(@_);
	my $destination = shift(@_);
	
	if(defined($source) and defined($destination)){
		foreach my $svar (keys(%{$source})){
			$destination->{uc($svar)} = $source->{$svar};
		}
	}
}

sub getOrderedSectionMembers(){
	my $config_name		= uc(shift(@_));
	my $section_name	= uc(shift(@_));
	
	#&debug('BILLING', 'INFO', "Config Name is $config_name.");
	#&debug('BILLING', 'INFO', "Section Name is $section_name.");
	
	my @parameters		= ();
	
	if(exists($configs{$config_name})){
		@parameters		= $configs{$config_name}->Parameters($section_name);
	}
	
	#foreach my $o (@parameters){
	#	&debug('BILLING', 'INFO', $o);
	#}
	
	return @parameters;
}

1;
