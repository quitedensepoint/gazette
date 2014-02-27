package Playnet::Debug;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

@ISA 	= qw(Exporter);
@EXPORT = qw(startDebug stopDebug debug newLine);

my %channels	= ();
my @timestats	= localtime(time);

$timestats[5] 	+= 1900;
$timestats[4] 	+= 1;

sub startDebug(){
	my $channel		= uc(shift(@_));
	my $outfile 	= shift(@_);
	my $stdout 		= shift(@_);
	my $overwrite	= shift(@_);
	my $ender		= shift(@_);
	
	if(!exists($channels{$channel})){
		my %new_channel = ();
		
		$ender		= (defined($ender) and $ender == 0) ? '': ".$timestats[4]-$timestats[3]-$timestats[5]";
		
		no strict 'refs';
		
		if(defined($overwrite) and $overwrite == 1){
			open($channel,">$outfile$ender") or warn "Unable to open log channel $channel." and return;
		}
		else{
			open($channel,">>$outfile$ender") or warn "Unable to open log channel $channel." and return;
		}
	
		$new_channel{'OUT'}		= *$channel;
		$new_channel{'STDOUT'}	= (defined($stdout) and $stdout == 1) ? 1: 0;
		
		$channels{$channel}		= \%new_channel;
		
		&newLine($channel);
		&debug($channel, '>>>>>>>>>', "Log Opened at ".localtime(time));
		
		use strict 'refs';
	}
}

sub stopDebug(){
	foreach my $channel (keys(%channels)){
		&debug($channel, '<<<<<<<<<', "Log Closed at ".localtime(time));
		close($channels{$channel}->{'OUT'});
	}
}

sub debug(){
	my $channel		= uc(shift(@_));
	my $level		= shift(@_);
	my $output 		= shift(@_);
	my $dressing	= shift(@_);
	my $stamp 		= time;
	
	$level		= (defined($level)) ? uc($level): 'INFO';
	$dressing	= (defined($dressing) and $dressing == 0) ? 0: 1;
	
	foreach my $c (keys(%channels)){
		if($channel eq $c or $channel eq 'ALL'){
			my @lines 	= split(/\n/,$output);
			my $out		= $channels{$c}->{'OUT'};
			
			foreach my $line (@lines){
				
				my $line = ($dressing == 0) ? sprintf("%s\n",$line): sprintf("%-12s%-12s%-s\n",$stamp,"[$level]",$line);
				
				print $out $line;
				
				if($channels{$c}->{'STDOUT'} == 1){
					print $line;
				}
			}
		}
	}
}

sub newLine(){
	my $channel		= uc(shift(@_));
	
	foreach my $c (keys(%channels)){
		if($channel eq $c or $channel eq 'ALL'){
			my $out		= $channels{$c}->{'OUT'};
			
			print $out "\n";
				
			if($channels{$c}->{'STDOUT'} == 1){
				print "\n";
			}
		}
	}
}

1;
