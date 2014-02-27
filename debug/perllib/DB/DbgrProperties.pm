# DbgrProperties.pm -- Move all the property-handling code
# into this module.

package DB::DbgrProperties;

$VERSION = 0.10;
require Exporter;
@ISA = qw(Exporter);
@EXPORT = qw(
	     doPropertySetInfo
	     emitContextNames
	     emitContextProperties
	     emitEvaluatedPropertyGetInfo
	     emitFinalPropertyValue
	     figureEncoding
	     getContextProperties
	     getFullPropertyInfoByValue
	     getPropertyInfo
	     getPropertyValue
	     sortArrayOfNames
	     GlobalVars
	     LocalVars
	     FunctionArguments
	     PunctuationVariables
	     );
@EXPORT_OK = ();

use DB::DbgrCommon;

# Internal sub declarations

sub adjustLongName($$$);
sub makeFullName($$);

# And recursively-called exported routines:

sub figureEncoding($);
sub getFullPropertyInfoByValue($$$$$$);

# Constants

use constant LocalVars => 0;
use constant GlobalVars => 1;
use constant FunctionArguments => 2;
use constant PunctuationVariables => 3;

$ldebug = 0;

# Private Data

%contextProperties = (Globals => GlobalVars,
		      Locals => LocalVars,
		      Arguments => FunctionArguments,
		      Special => PunctuationVariables);
@contextProperties = sort { $contextProperties{$a} <=> $contextProperties{$b} } keys %contextProperties;

@_punctuationVariables = ('$_', '$?', '$@', '$.', '@+', '@-', '$+', '$&', '$!', '$\'', '$`', '$$', '$0');


# Exported subs

=head1 postconditions

This function does one of three things:

1. Throw an exception: let the caller deal with it, and formulate
an error message.

2. Assign a value to a local value if it's a non-top-level stack
Return undef

3. Return [$property_long_name, undef, 1]
and let the caller do an eval to carry out the assignment.

=cut

sub doPropertySetInfo($$$) {
    my ($cmd,
	$transactionID,
	$property_long_name) = @_;
    
    if (!defined $property_long_name) {
	return makeErrorResponse($cmd,
				 $transactionID,
				 DBP_E_InvalidOption,
				 "-n full-property-name missing");
    } else {
	# In Perl these can be modified.  Setting $_[x] to a value
	# changes the underlying object if it isn't constant.
	# Other changes will be ignored.
	return [$property_long_name, undef, 1];
    }
}

sub emitContextNames($$) {
    my ($cmd, $transactionID) = @_;
    my $res = sprintf(qq(%s\n<response %s command="%s" 
			 transaction_id="%s" >),
		      xmlHeader(),
		      namespaceAttr(),
		      $cmd,
		      $transactionID);
    # todo: the spec suggests that locals be the default,
    # but globals (package globals) make more sense for Perl.
    for ($i = 0; $i <= $#contextProperties; $i++) {
	$res .= sprintf(qq(<context name="%s" id="%d" />\n),
			$contextProperties[$i],
			$i);
    }
    $res .= "\n</response>\n";
    printWithLength($res);
}

sub emitContextProperties($$$) {
    my ($cmd,
	$transactionID,
	$nameValuesARef) = @_;
    
    my $res = sprintf(qq(%s\n<response %s command="%s"
			 context_id="%d"
			 transaction_id="%s" >),
		      xmlHeader(),
		      namespaceAttr(),
		      $cmd,
		      $context_id,
		      $transactionID);
    my @results = @$nameValuesARef;
    my $numVars = scalar @results;
    for (my $i = 0; $i < $numVars; $i++) {
	my $result = $results[$i];
	my $name = $result->[0];
	my $val = $result->[1];
	eval {
	    my $property = getFullPropertyInfoByValue($name,
						      $name,
						      $val,
						      $settings{max_data}[0],
						      0,
						      0);
	    # dblog("emitContextProperties: getFullPropertyInfoByValue => $property") if $ldebug;
	    $res .= $property;
	};
	if ($@) {
	    dblog("emitContextProperties: error [$@]") if $ldebug;
	}
    }
    $res .= "\n</response>";
    printWithLength($res);
}


sub emitEvaluatedPropertyGetInfo($$$$$$$) {
    my ($cmd,
	$transactionID,
	$nameAndValue,
	$property_long_name, # For the response things.
	$propertyKey,
	$maxDataSize,
	$pageIndex) = @_;

    my $res = sprintf(qq(%s\n<response %s command="%s" 
			 transaction_id="%s" >),
		      xmlHeader(),
		      namespaceAttr(),
		      $cmd,
		      $transactionID);
    my $finalVal = $nameAndValue->[NV_VALUE];
    
    my $finalName = makeFullName($property_long_name, $propertyKey);
    $res .= getFullPropertyInfoByValue($propertyKey || $finalName, # name
				       $finalName,
				       $finalVal,
				       $maxDataSize,
				       $pageIndex, # page
				       0, # current depth
				       );
    $res .= "\n</response>";
    printWithLength($res);
}

# Return a ref to an array of [name, value, needValue] triples
#
# Some values can be evaluated in this scope, but non-package values
# and locals at the top-level will need to be evaluated in the
# debugger's main loop.

sub getContextProperties($$) {
    my ($context_id, $packageName) = @_;
    
    # Here just show the top-level.
    my @results;
    local $settings{max_depth}[0] = 0;
    if ($context_id == GlobalVars) {
	# Globals
	# Variables on the calling frame
	# To get the vars, we need the '::' at the end
	$packageName =~ s/(?<!::)$/::/;
	my @results;
	eval {
	    require IO::Scalar;
	    my @data;
	    my $data;
	    my $ah = IO::Scalar->new(\$data);
	    defined &main::dumpValue || do 'dumpvar.pl';
	    if (defined &main::dumpValue) {
		my $oh = select $ah;
		# must detect sigpipe failures  - not catching
		# then will cause the debugger to die.
		eval {
		    if ($^V ge v5.8) {
			# Interface changed with 5.8
			&main::dumpvar(
				       $packageName,
				       -1, # dumpDepth
				       ()
				       );
		    } else {
			&main::dumpvar(
				       $packageName,
				       ()
				       );
		    }
		};

		# The die does not need to include the $@, because 
		# it will automatically get propagated for us.
		if ($@) {
		    dblog("eval globals: $@\n");
		} else {
		    my @hits = ($data =~ m/^([\$\@\%][\w_]+)\s+=\s+/gm);
		    foreach my $h (@hits) {
			# dblog("examining '$h' in package [$packageName]\n") if $ldebug;
			my ($h1, $h2) = split(//, $h, 2);
			if ($h1 ne "\$") {  # q($) confuses emacs
			    # dblog("not a scalar ($h1)\n");
			    # Get the main evaluator to eval this
			    push @results, ["$h", undef, 1];
			} elsif (exists $packageName->{$h2}) {
			    # dblog("'$h' lives in package '$packageName'\n") if $ldebug;
			    push @results, ["$h", $tmp, 0];
			} else {
			    # dblog("'$h' is in no package\n") if $ldebug;
			    push @results, ["$h", undef, 1];
			}
		    }
		}
		select $oh;
  	        if ($ldebug) {
		    require Data::Dump;
		    dblog("vars:", Data::Dump::dump(@results), "\n");
	        }
	    } else {
		dblog("getContextProperties -- don't have dumpValue");
	    }
	};
	if ($@) {
	    dblog($@);
	    die "code:(1):error:($@)";
	}
	return \@results;
    } elsif ($context_id == FunctionArguments) {
        # This should be evaluated in the caller, in the main event
	# loop in DB::DB
	@results = (['@_', undef, 1]);
	return \@results;
    } elsif ($context_id == PunctuationVariables) {
	my ($packageName, $filename, $line) = caller(1);
	my %vals;
	my @results;
	foreach my $pv (@_punctuationVariables) {
	    push (@results, [$pv, undef, 1]);
	}
	return \@results;
	
    } else {
	die sprintf("code:%d:error:%s",
		    302,
		    ("Not ready to evaluate "
		     . $contextProperties[$context_id]
		     . ' variables'));
    }
}
 
sub emitFinalPropertyValue($$$$) {
    my ($cmd,
	$transactionID,
	$property_long_name,
	$val) = @_;
    my $size = length $val;
    ($encoding, $encVal) = figureEncoding($val);
    my $res = sprintf(qq(%s\n<response %s command="%s" 
			 transaction_id="%s"
			 size="%d"
			 encoding="%s">%s</response>),
		      xmlHeader(),
		      namespaceAttr(),
		      $cmd,
		      $transactionID,
		      $size,
		      $encoding,
		      $encVal);
    printWithLength($res);
}


# This routine either returns a (name, value, evalRequest) array
# Locals are evaluated here everywhere but at the top-level
# Everthing else we attempt to evaluate here.  If we fail, we'll
# get the DB::DB main loop to eval it, and then the second
# function will fill in the full value.

# Precondition: we're inside an eval block.

sub getPropertyValue($$$$$$$) {
    my ($cmd,
	$context_id,
	$requestedStackDepth,
	$currStackSize,
	$packageName,
	$property_long_name,
	$callDepthAdjustment) = @_;

    my $finalVal;
    if ($context_id == FunctionArguments) {
	# Get the args, and then get the full property
        my @xargs;
	{
	    package DB;
	    my ($packageName, $filename, $line) = caller($requestedStackDepth + $callDepthAdjustment - 1);
	    dblog("getPropertyValue: curr args are [", join(", ", @DB::args), "]");
	    @xargs = @DB::args;
	}
	if (@xargs) {
	    my $indexer = $property_long_name;
	    if ($indexer =~ m/^\$_\[(\d+)\]/) {
		return [$property_long_name, $xargs[$indexer], 0];
	    }
	}
    }
    return [$property_long_name, undef, 1];
}


sub getPropertyInfo($$) {
    my ($property_long_name, $propertyKey) = @_;

    # Invariant: FunctionArguments are handled by the caller.
    
    my $finalName = makeFullName($property_long_name, $propertyKey);
    return [$finalName, undef, 1];
}
   
sub propertyTagSpacer($) {
    my ($currentDepth) = @_;
    return ("\n" . ('  ' x $currentDepth));
}

sub figureEncoding($) {
    my ($val) = @_;
    my ($encVal);
    my $encoding = $settings{data_encoding}->[0];
    if ($encoding eq 'none' || $encoding eq 'binary') {
	if ($val =~ m/[\x00-\x08\x0b\x0c\x0e-\x1f]/) {
	    # Override
	    $encoding = 'base64';
	}
    }
    $encVal = encodeData($val, $encoding);
    if ($encoding eq 'none' || $encoding eq 'binary') {
	$encVal = xmlEncode($encVal);
    }
    return ($encoding, $encVal);
}

sub getFullPropertyInfoByValue($$$$$$) {
    my ($name,
	$fullname,
	$val,
	$maxDataSize,
	$pageIndex,
	$currentDepth,
	) = @_;
    # dblog("getFullPropertyInfoByValue: (@_)\n");
    my $res = '<property';
    if ($currentDepth > 0) {
	$res .= propertyTagSpacer($currentDepth);
    }
    $res .= sprintf(qq( name="%s"), xmlAttrEncode($name));
    $res .= sprintf(qq( fullname="%s"), xmlAttrEncode($fullname));
    my $typeString;
    my $hasChildren = 0;
    my $numChildren;
    my $className;
    my ($h1, $h2) = split(//, $fullname, 2);
    my $encVal = undef;
    my $encValLength = undef;
    my $refstr = "";
    my $address;
    if (!defined $val) {
	$typeString = 'null';
    } else {
	# Unlike getPropertyInfo, this is where we find
	# arrays and hashes
	if ($refstr = ref $val) {
	    my $stringifiedVal = "" . $val;
	    if ($refstr =~ /^ARRAY/) {
		$typeString = 'array';
		$numChildren = scalar @$val;
		$hasChildren = $numChildren >= 1;
		($address) = ($stringifiedVal =~ m/^ARRAY\(0x(.*)\)$/i);
	    } elsif ($refstr =~ /^HASH/) {
		$typeString = 'hash';
		$numChildren = scalar keys %$val;
		$hasChildren = $numChildren >= 1;
		($address) = ($stringifiedVal =~ m/^HASH\(0x(.*)\)$/i);
	    } else {
		($typeString, $numChildren, $className) = analyzeVal($val);
		$hasChildren = $numChildren && 1;
		if ($className) {
		    $typeString = $className;
		    if ($maxDataSize >= 0 && $dataSize > $maxDataSize) {
			# dblog("getFullPropertyInfoByValue: truncating data down to $maxDataSize bytes:\n[$val]\n");
			substr($val, $maxDataSize) = "";
		    }
		    ($encoding, $encVal) = figureEncoding($val);
		    $res .= sprintf(qq( encoding="%s"), $encoding);
		    $encValLength = length($encVal);
		} else {
		    ($address) = ($stringifiedVal =~ m/^.+\(0x(.*)\)$/i);
		}
	    }
	} else {
	    $hasChildren = 0;
	    # It's a scalar -- get the underlying value and classify.
	    $dataSize = length($val);
	    if ($maxDataSize >= 0 && $dataSize > $maxDataSize) {
		# dblog("getFullPropertyInfoByValue: truncating data down to $maxDataSize bytes:\n[$val]\n");
		substr($val, $maxDataSize) = "";
	    }
	    ($encoding, $encVal) = figureEncoding($val);
	    $res .= sprintf(qq( encoding="%s"), $encoding);
	    $encValLength = length($encVal);
	    $typeString = getCommonType($val);
	}
    }
    $res .= qq( type="$typeString");
    $res .= qq( constant="0");
    if ($hasChildren) {
	$res .= qq( children="1" numchildren="$numChildren");
	if (defined $address) {
	    $res .= qq( address="$address");
	}
    } else {
	$res .= qq( children="0");
    }

    if ($hasChildren) {
	$res .= qq( size="0");
	$res .= qq( page="$pageIndex");
	$res .= sprintf(qq( pagesize="%d"), $settings{max_children}[0]);
	# Get each child property
	if ($currentDepth < $settings{max_depth}[0]) {
	    my $childrenPerPage = $settings{max_children}[0];
	    my $startIndex = $pageIndex * $childrenPerPage;
	    my $endIndex = $startIndex + $childrenPerPage - 1;
	    if ($typeString eq 'array'
		|| $refstr =~ /=ARRAY\(0x.*\)/
		|| "$val" =~ /=ARRAY\(0x.*\)/) {
		my $arraySize = scalar @$val;
		#### ???? $res .= sprintf(qq( numchildren="%d"), $arraySize);
		$res .= qq(>);
		if ($startIndex < $arraySize) {
		    if ($endIndex >= $arraySize) {
			$endIndex = $arraySize - 1;
		    }
		    for (my $i = $startIndex; $i <= $endIndex; $i++) {
			my ($newInnerName, $newFullName) =
			    adjustLongName($fullname, $i, 1);
			my $innerProp =
			    getFullPropertyInfoByValue($newInnerName,
						       $newFullName,
						       $val->[$i],
						       $maxDataSize,
						       # For inner children,
						       # show first page
						       0,
						       $currentDepth + 1);
			$res .= "$innerProp";
		    }
		}
	    } elsif ($typeString eq 'hash'
		     || $refstr =~ /=HASH\(0x.*\)/
		     || "$val" =~ /=HASH\(0x.*\)/) {
		my %hval = %$val;
		my @keys = sort keys %hval;
		my $arraySize = scalar @keys;
		#### ???? $res .= sprintf(qq( numchildren="%d"), $arraySize);
		$res .= qq(>);
		if ($startIndex < $arraySize) {
		    if ($endIndex >= $arraySize) {
			$endIndex = $arraySize - 1;
		    }
		    for (my $i = $startIndex; $i <= $endIndex; $i++) {
			my $k = $keys[$i];
			my ($newInnerName, $newFullName) =
			    adjustLongName($fullname, $k, 0);
			my $innerProp =
			    getFullPropertyInfoByValue($newInnerName,
						       $newFullName,
						       $val->{$k},
						       $maxDataSize,
						       # For inner children,
						       # show first page
						       0,
						       $currentDepth + 1);
			$res .= "$innerProp";
		    }
		}
	    } else {
		# Objects just have one child
		#### $res .= qq( numchildren="1");
		$res .= qq(>);
		my $innerProp =
		    getFullPropertyInfoByValue("->",
					       "\${$fullname}",
					       $$val,
					       $maxDataSize,
					       # For inner children,
					       # show first page
					       0,
					       $currentDepth + 1);
		$res .= "$innerProp";
	    }
	} else {
	    # End the start-tag.
	    $res .= qq( >);
	}
	if ($currentDepth > 0) {
	    $res .= ("</property" . propertyTagSpacer($currentDepth) . '>');
	} else {
	    $res .= qq(</property>\n);
	}
    } else {
	$res .= qq( size="$encValLength") if defined $encValLength;
	$res .= sprintf(qq(><![CDATA[%s]]></property%s>%s),
			$encVal,
			$currentDepth == 0 ? "" : propertyTagSpacer($currentDepth),
			$currentDepth == 0 ? "\n" : "");
    }
    # dblog("getFullPropertyInfoByValue: \{$res}\n");
    return $res;
}

# Called only by wrapVars, so it does no expansion
# It's only used to provide info on the variables for 
# a given context

sub analyzeVal($) {
    my ($val) = @_;
    if (!defined $val) {
	return ('null', 0, undef);
    } elsif (!(ref $val)) {
	return ('null', 0, undef);
    }
    my $refstr = ref $val;
    if ($refstr =~ /^ARRAY/) {
	return ('array', scalar @$val, undef);
    } elsif ($refstr =~ /^HASH/) {
	return ('hash', scalar keys %$val, undef);
    } elsif ($refstr =~ /^REF/ || $refstr =~ /^SCALAR/) {
	return ('object', 1, undef);
    } elsif ($refstr =~ /^CODE/) {
	return ('CODE', 0, undef);
    } elsif ("$val" =~ /$refstr=/) {
	my $strVal = "$val";
	if ($strVal =~ /=HASH\(0x\w+\)/) {
	    return ('object', scalar keys %$val, $refstr);
	} elsif ($strVal =~ /=ARRAY\(0x\w+\)/) {
	    return ('object', scalar @$val, $refstr);
	} else {
	    return ('object', 0, $refstr);
	}
    } else {
	# Return whatever it is.
	$refstr =~ s/=.*//;
	return ($refstr, 1, undef);
    }
}

sub sortArrayOfNames {
    my ($a1, $a2) = split(//, $a, 2);
    my ($b1, $b2) = split(//, $b, 2);
    return ($a2 cmp $b2 || $a1 cmp $b1);
}

#############################################################################

# Internal subs

sub adjustLongName($$$) {
    my ($fullname, $key, $isArray) = @_;
    if ($isArray) {
	if ($fullname =~ m/^(\@)(.*)/) {
	    return ("[$key]", sprintf('$%s[%d]', $2, $key));
	} else {
	    return ("->[$key]", "${fullname}->[$key]");
	}
    } else {
	if ($key =~ /^-?[a-zA-Z_]\w*$/) {
	    # Do nothing
	} else {
	    if ($key =~ /[\\\']/) {
		$key =~ s/([\\\'])/\\$1/g;
	    }
	    $key = "'$key'";
	}
	if ($fullname =~ m/^(\%)(.*)/) {
	    # Verify that single-quotes won't nest.
	    return ("{$key}", sprintf(q($%s{%s}), $2, $key));
	} else {
	    return ("->{$key}", "${fullname}->{$key}");
	}
    }
}

sub makeFullName($$) {
    my ($property_long_name, $propertyKey) = @_;
    if (!$propertyKey) {
	return $property_long_name;
    } elsif ($property_long_name =~ /^[\@\%](.*)/) {
	return sprintf(q($%s%s), $1, $propertyKey);
    } else {
	return sprintf(q(%s->%s), $property_long_name, $propertyKey);
    }
}

1;
