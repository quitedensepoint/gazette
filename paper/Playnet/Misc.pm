package Playnet::Misc;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

@ISA 		= qw(Exporter);
@EXPORT 	= qw(getRandomNumber hashMerge miniParser getRDPChangeAdj);

sub getRandomNumber($$){
	
	my $min = shift(@_);
	my $max = shift(@_);
	
    return $min if $min == $max;
	
	($min, $max) = ($max, $min)  if  $min > $max;
	
	return $min + int(rand(1 + $max - $min));
	
}  

sub hashMerge(){
	
	my $to		= shift(@_);
	my $from	= shift(@_);
	
	if(defined($to) and defined($from)){
	
		foreach my $f (keys(%$from)){
			$to->{$f} = $from->{$f};
		}
		
	}
	
}

sub miniParser(){
	
	my $template 	= shift(@_);
	my $vars		= shift(@_);
	
	foreach my $v (keys(%{$vars})){
		if($template =~ /%$v%/i){
			$template =~ s/%$v%/$vars->{$v}/gi;
		}
	}
	
	return $template;
}

sub getRDPChangeAdj(){
	
	my $change = shift(@_);
	
	if($change < 3){
		return 'small';
	}
	elsif($change < 5){
		return 'noticable';
	}
	elsif($change < 10){
		return 'sizable';
	}
	elsif($change < 20){
		return 'significant';
	}
	
	return 'huge';
}  

1;
