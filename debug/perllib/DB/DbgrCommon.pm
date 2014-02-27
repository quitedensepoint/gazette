#
# DbgrCommon.pm -- common routines used by all the debugger modues.

package DB::DbgrCommon;

$VERSION = 0.10;
require Exporter;
@ISA = qw(Exporter);
@EXPORT = qw(encodeData
	     endPropertyTag getCommonType
             isFloat
	     makeErrorResponse namespaceAttr
	     printWithLength
	     setDefaultOutput
	     xmlHeader xmlEncode xmlAttrEncode

                    DBP_E_NoError
                    DBP_E_ParseError
                    DBP_E_DuplicateArguments
                    DBP_E_InvalidOption
                    DBP_E_CommandUnimplemented
                    DBP_E_CommandNotAvailable
                    DBP_E_UnrecognizedCommand

                    DBP_E_CantOpenSource

                    DBP_E_BreakpointNotSet
                    DBP_E_BreakpointTypeNotSupported
                    DBP_E_BreakpointStateInvalid
                    DBP_E_NoSuchBreakpoint
	            DBP_E_CantSetProperty
                    DBP_E_PropertyEvalError

                    DBP_E_CantGetProperty
                    DBP_E_StackDepthInvalid
                    DBP_E_ContextInvalid

	     NV_NAME
	     NV_VALUE
	     NV_NEED_MAIN_LEVEL_EVAL
	     NV_UNSET_FLAG

	     %settings
	     dblog
	     );

# Leave the other parts of the logger unexported.

@EXPORT_OK = qw();

# Error codes

use constant DBP_E_NoError => 0;
use constant DBP_E_ParseError => 1;
use constant DBP_E_DuplicateArguments => 2;
use constant DBP_E_InvalidOption => 3;
use constant DBP_E_CommandUnimplemented => 4;
use constant DBP_E_CommandNotAvailable => 5;

#todo: add this to protocol
use constant DBP_E_UnrecognizedCommand => 6;

use constant DBP_E_CantOpenSource => 100;

use constant DBP_E_BreakpointNotSet => 200;
use constant DBP_E_BreakpointTypeNotSupported => 201;
use constant DBP_E_BreakpointStateInvalid => 204;
use constant DBP_E_NoSuchBreakpoint => 205;
use constant DBP_E_PropertyEvalError => 206;
use constant DBP_E_CantSetProperty => 207;

use constant DBP_E_CantGetProperty => 300;
use constant DBP_E_StackDepthInvalid => 301;
use constant DBP_E_ContextInvalid => 302;

use constant NV_NAME => 0;
use constant NV_VALUE => 1;
use constant NV_NEED_MAIN_LEVEL_EVAL => 2;
use constant NV_UNSET_FLAG => 3;

# Real simple logging

$doLogging = 0;
$logFH = undef;

# enableLogger(filename || filehandle);
# dies if it fails to do logging

sub enableLogger {
    if (!@_) {
	# Enable $doLogging if we have an existing log file handle
	$doLogging = 1 if $logFH;
	printf $logFH "Logging reenabled at %s\n", ('' . localtime(time()));
	return;
    }
    my $outLogName = shift;
    return unless $outLogName;
    if (ref $outLogName eq 'GLOB') {
	# Make sure we can write to it -- we die if we can't
	printf $outLogName "Logging enabled at %s\n", ('' . localtime(time()));
	$logFH = $outLogName;
    } else {
	if (-d $outLogName) {
	    $outLogName =~ s@\\@/@g;
	    $outLogName =~ s@/$@@;
	    $outLogName .= "/perl5db.log";
	}
	open my $ofh, ">", $outLogName or die "Failed to open $outLogName for writing: $!";
	my $oh = select $ofh;
	$| = 1;
	select $oh;
	printf $ofh "Logging enabled at %s\n", ('' . localtime(time()));
	$logFH = $ofh;
    }
    $doLogging = 1;
}

sub disableLogger {
    printf $logFH "Logging disabled at %s\n", ('' . localtime(time()));
    $doLogging = 0;
}

sub closeLogger {
    if ($logFH) {
	printf $logFH "Logging ended at %s\n", ('' . localtime(time()));
	close ($logFH);
	$logFH = undef;
    }
    $doLogging = 0;
}

sub dblog {
    if (@_ && $doLogging && $logFH) {
	print $logFH @_;
	# End with a newline
	print $logFH "\n" unless substr($_[-1], -1, 1) eq "\n";
    }
}

# our ($OUT, $ldebug);

sub setDefaultOutput($) {
    $OUT = shift;
}

sub encodeData($;$) {
    my ($str, $encoding) = @_;
    my $finalStr;
    my $currDataEncoding = defined $encoding ? $encoding : $settings{data_encoding}->[0];
    $finalStr = $str;
    eval {
	if ($currDataEncoding eq 'none' || $currDataEncoding eq 'binary') {
	    $finalStr = $str;
	} elsif ($currDataEncoding eq 'urlescape') {
	    require 'CGI/Util.pm';
	    $finalStr = CGI::Util::escape($str);
	} elsif ($currDataEncoding eq 'base64') {
	    require 'MIME/Base64.pm';
	    $finalStr = MIME::Base64::encode_base64($str);
	} else {
	    dblog("Converting $str with unknown encoding of $currDataEncoding\n") if $ldebug;
	    $finalStr = $str;
	}
    };
    if ($ldebug) {
	if ($@) {
	    $str = (substr($str, 0, 100) . '...') if length($str) > 100;
	    dblog("encodeData('$str') => [$@]\n");
	} else {
	    dblog("encodeData('$str') => ['$finalStr']\n");
	}
    }
    return $finalStr;
}

sub endPropertyTag($$) {
    my ($encVal, $encoding) = @_;
    my $res;
    if (defined $encVal && length $encVal > 0) {
#		$res .= sprintf(qq(>%s</property>\n), ($encVal));
	$res = sprintf(qq(><![CDATA[%s]]></property>\n), ($encVal));
    } else {
	$res = qq(/>\n);
    }
    return $res;
}

# If other names are used here, be sure to update the
# list in sub DB::emitTypeMapInfo

sub getCommonType($) {
    my ($val) = @_;
    if (! defined $val) {
	return 'null';
    }
    my $r;
    if ($r = ref $val) {
	if ($r =~ /^ARRAY/) {
	    return 'array';
	} elsif ($r =~ /^HASH/) {
	    return 'hash';
	} else {
	    # classes and refs to scalars
	    return 'object';
	}
    } elsif ($val =~ /^\d+$/) {
	return 'int';
    } elsif (isFloat($val)) {
	return 'float';
    } else {
	return 'string';
    }
}

sub isFloat($) {
    my ($val) = @_;
    return $val =~ /^
                    (?:
  		     [-+]?           # leading sign always optional
		     (?:
		        \d+          # required base
		        (?:\.\d*)?   # optional fractional part
	             )|(?:
		       \.            # required decimal pt and number
		       \d+
		       )
		     )
		     (?:[eE][-+]?\d+)?  # exponent always optional
		     $/x;
}

sub makeErrorResponse($$$$) {
    my ($cmd, $transactionID, $code, $error) = @_;
    printWithLength(sprintf
		    (qq(%s\n<response %s command="%s" 
			transaction_id="%s" ><error code="%d" apperr="4">
			<message>%s</message>
			</error></response>),
		     xmlHeader(),
		     namespaceAttr(),
		     $cmd,
		     $transactionID,
		     $code,
		     $error));
    return undef;
}

sub namespaceAttr() {
  return 'xmlns="urn:debugger_protocol_v1"';
}

sub printWithLength {
  local $mainArg = shift;
  local $argLen = length($mainArg);
  local $finalStr = sprintf("%d\0%s\0", $argLen, $mainArg);
  # dblog $finalStr;
  print $OUT $finalStr;
}
  
# Like xmlEncode, but escape the quotes

sub xmlAttrEncode {
    my ($str) = @_;
    $str =~ s/\&/&amp;/g;
    $str =~ s/</&lt;/g;
    $str =~ s/>/&gt;/g;
    $str =~ s/([\x00-\x08\x0b\x0c\x0e-\x1f\"\'\x7f-\xff])/'&#' . ord($1) . ';'/eg;
    eval {
	require 5.8;
	eval q!$str =~ s/([\x{01000}])/'&#' . ord($1) . ';'/eg!;
    };
    return $str;
}

sub xmlEncode {
    my ($str) = @_;
    $str =~ s/\&(?!\w+;)/&amp;/g;
    $str =~ s/</&lt;/g;
    $str =~ s/>/&gt;/g;
    $str =~ s/([\x00-\x08\x0b\x0c\x0e-\x1f\x7f-\xff])/'&#' . ord($1) . ';'/eg;
    eval {
	require 5.8;
	eval q!$str =~ s/([\x{01000}])/'&#' . ord($1) . ';'/eg!;
    };
#    $str =~ s/\]\]>/]<!-- -->]>/;
    # No need to escape quotes.
    return $str;
}

sub xmlHeader() {
  return '<?xml version="1.0" encoding="' . $settings{encoding}->[0] . '" ?>';
}

1;
