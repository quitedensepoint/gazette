#!/usr/bin/perl -w

use strict;

chomp(@ARGV);

print &estimateCompletion($ARGV[0], $ARGV[1]);

exit;

sub estimateCompletion()
{
	my $goal 		= shift;
	my $producers	= shift;
	
	my $seconds = ($goal / $producers) * 600;
	
	if($seconds > 60)
	{
		my $minutes	= int($seconds / 60);
		
		if($minutes > 60)
		{
			my $hours = int($minutes / 60);
			
			if($hours > 24)
			{
				my $days = int($hours / 24);
				
				return $days."d ".($hours - ($days * 24))."h";
			}
			else
			{
				return $hours."h ".($minutes - ($hours * 60))."m";
			}
		}
		else
		{
			return $minutes."m ".($seconds - ($minutes * 60))."s";
		}
	}
	else
	{
		return $seconds."s";
	}
}
