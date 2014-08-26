package Playnet::Database;

use strict;
use DBI;

require Exporter;

use vars qw(@ISA @EXPORT);

@ISA 		= qw(Exporter);
@EXPORT 	= qw(addDatabase addStatement freeStatement freeDatabases doUpdate doSelect doQuery doNextRow);

my %databases		= ();
my %statements		= ();

sub addDatabase(){
	my $database_name	= shift(@_);
	my $connect_string	= shift(@_);
	my $username		= shift(@_);
	my $password		= shift(@_);
	my $auto_commit		= shift(@_);
	
	if(!defined($auto_commit)){
		$auto_commit = 1;
	}
	
	if(not exists($databases{$database_name})){
		eval{
			$databases{$database_name} = DBI->connect($connect_string, $username, $password,{'RaiseError' => 1, 'AutoCommit' => $auto_commit});
		};
		if($@){
			die "Unable to add $database_name: $@.\n";
			return 0;
		}
	}
	
	return 1;
}

sub addStatement(){
	my $database_name	= shift(@_);
	my $statement_name	= shift(@_);
	my $statement		= shift(@_);
	
	if(exists($databases{$database_name})){
		if(exists($statements{$statement_name})){
			eval{
				$statements{$statement_name}->finish();
			};
			
			delete($statements{$statement_name});
		}
		
		eval{
			$statements{$statement_name} = $databases{$database_name}->prepare($statement);	
		};
		if($@){
			warn("Unable to add $statement_name for $database_name: $@.");
			return 0;
		}
	}
	
	return 1;
}

sub freeStatement(){
	my $statement_name	= shift(@_);
	
	if(exists($statements{$statement_name})){
		eval{
			$statements{$statement_name}->finish();
		};
		
		delete($statements{$statement_name});
	}
}

sub freeDatabases(){
	
	foreach my $s (keys(%statements)){
		$statements{$s}->finish();
	}
	
	foreach my $d (keys(%databases)){
		#$databases{$d}->commit();
		$databases{$d}->disconnect();
	}
}

sub doUpdate(){
	my $handle 		= shift(@_);
	my @arguments 	= @_;
	my $rv			= 0;
	
	my $start		= time;
	
	eval{
		$rv = $statements{$handle}->execute(@arguments);
	};
	if($@){
		warn("Update Error on $handle: $@");
	}
	
	#warn($$.") Update $handle took ".(time - $start)." seconds to complete.");
	
	return ($rv ne '0E0' and $rv > 0) ? 1: 0;
}

sub doSelect(){
	my $handle 		= shift(@_);
	my $type 		= shift(@_);
	my @arguments 	= @_;
	
	eval{
		my $start = time;
		$statements{$handle}->execute(@arguments);
		#warn($$.") Select $handle took ".(time - $start)." seconds to complete.");
	};
	if($@){
		warn("Select Error on $handle: $@");
		return undef;
	}
	else{
		
		if($type eq 'hashref_row'){
			return ($statements{$handle}->fetchrow_hashref());
		}
		elsif($type eq 'arrayref_row'){
			return ($statements{$handle}->fetchrow_arrayref());
		}
		elsif($type eq 'arrayref_all'){
			return ($statements{$handle}->fetchall_arrayref());
		}
		elsif($type eq 'hashref_all'){
			return ($statements{$handle}->fetchall_arrayref({}));
		}
		elsif($type eq 'queued'){
			return;
		}
		else{
			return ($statements{$handle}->fetchrow_array());
		}
	}
}

sub doQuery(){
	
	my $database_name	= shift(@_);
	my $statement_name	= shift(@_);
	my $query 			= shift(@_);
	my $return_type		= shift(@_);
	
	&addStatement($database_name, $statement_name, $query);
	
	return ($query !~ /^\s*SELECT /i) ? &doUpdate($statement_name): &doSelect($statement_name, $return_type);
	
}

sub doNextRow(){
	
	my $handle 		= shift(@_);
	my $type 		= shift(@_);
	
	if($type eq 'hashref_row'){
		return ($statements{$handle}->fetchrow_hashref());
	}
	elsif($type eq 'arrayref_row'){
		return ($statements{$handle}->fetchrow_arrayref());
	}
	else{
		return ($statements{$handle}->fetchrow_array());
	}
	
}

1;
