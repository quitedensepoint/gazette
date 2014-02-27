# RedirectStdOutput.pm
# 
# Module for redirecting standard output/error.
#
# Copyright (C) 2003 by ActiveState, a division of Sophos PLC.  
# All Rights Reserved.

package DB::RedirectStdOutput;

$VERSION = 0.10;
require Exporter;
@ISA = qw(Exporter);
@EXPORT = qw(
	     DBGP_Redirect_Disable
	     DBGP_Redirect_Copy
	     DBGP_Redirect_Redirect
	     );
@EXPORT_OK = ();

use strict;

use DB::DbgrCommon;
use DB::DbgrProperties (qw(figureEncoding));

use constant DBGP_Redirect_Disable => 0;
use constant DBGP_Redirect_Copy => 1;
use constant DBGP_Redirect_Redirect => 2;

sub TIEHANDLE {
    my ($class, $origHandle, $newFileHandle, $streamType, $redirectState) = @_;
    my $data = { h_new => $newFileHandle,
		 h_old => $origHandle,
		 streamType => $streamType,
		 redirectState => $redirectState,
	       };
    bless $data, $class;
}

sub doOutput($$$$) {
    my ($buf, $streamType, $redirectState, $h_old) = @_;
    if ($redirectState != DBGP_Redirect_Disable)  {
	my ($encoding, $encVal) = figureEncoding($buf);
	printWithLength(sprintf(qq(%s\n<stream %s
				   type="%s"
				   encoding="%s">%s</stream>\n),
				xmlHeader(),
				namespaceAttr(),
				$streamType,
				$encoding,
				$encVal,
				));
    }
    if ($redirectState != DBGP_Redirect_Redirect)  {
	print $h_old $buf;
    }
}


sub WRITE {
    my $self = shift;
    my $h_new = $self->{h_new};
    my $h_old = $self->{h_old};
    my $streamType = $self->{streamType};
    my $redirectState = $self->{redirectState};
    my ($buf, $len, $offset) = @_;
    substr($buf, $len) = "" if $len;
    doOutput($buf, $streamType, $redirectState, $h_old);
}

sub PRINT { 
    my $self = shift;
    my $h_new = $self->{h_new};
    my $h_old = $self->{h_old};
    my $streamType = $self->{streamType};
    my $redirectState = $self->{redirectState};
    my $buf = join('', @_);
    doOutput($buf, $streamType, $redirectState, $h_old);
}

sub PRINTF { 
    my $self = shift;
    my $h_new = $self->{h_new};
    my $h_old = $self->{h_old};
    my $streamType = $self->{streamType};
    my $redirectState = $self->{redirectState};
    my $fmt = shift;
    my $buf = sprintf($fmt, @_);
    doOutput($buf, $streamType, $redirectState, $h_old);
}

sub READ { 
}

sub READLINE {
}

sub GETC {
}

sub CLOSE {
}

sub UNTIE { 
    my $self = shift;
#    die "Not expected";
}

sub DESTROY {
    my $self = shift;
    my $h_new = $self->{h_new};
    my $h_old = $self->{h_old};
    close $h_new;
}

sub FILENO {
    my $self = shift;
    my $h_new = $self->{h_new};
    my $h_old = $self->{h_old};
    my $redirectState = $self->{redirectState};
    if ($redirectState != DBGP_Redirect_Disable)  {
	return fileno($h_new);
    } else {
	return fileno($h_old);
    }
}

1;
