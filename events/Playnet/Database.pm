package Playnet::Database;

use strict;
use DBI;
use lib ('/usr/local/community/events');
use Playnet::Debug;

require Exporter;

use vars qw(@ISA @EXPORT);

@ISA 		= qw(Exporter);
@EXPORT 	= qw(addDatabase addStatement freeDatabases freeStatement doUpdate doSelect);

# Set Up Oracle Environment Variable.
$ENV{'ORACLE_HOME'} = '/home/Oracle';

my %databases		= ();
my %statements		= ();

sub addDatabase(){
	my $database_name	= uc(shift(@_));
	my $connect_string	= shift(@_);
	my $username		= shift(@_);
	my $password		= shift(@_);
	
	if(!exists($databases{$database_name})){
		eval{
			$databases{$database_name} = DBI->connect($connect_string, $username, $password, {'RaiseError' => 1, AutoCommit=> 1});
		};
		if($@){
			&debug('DEBUG', 'ERROR', "Database connect failed for $database_name: $@");
			return 0;
		}
		
		return 1;
	}
	else{
		&debug('DEBUG', 'WARNING', "Unable to add database $database_name: database already exists");
	}
	
	return 0;
}

sub addStatement(){
	my $database_name	= uc(shift(@_));
	my $statement_name	= uc(shift(@_));
	my $statement		= shift(@_);
	
	if(exists($databases{$database_name})){
		if(not exists($statements{$statement_name})){
			eval{
				$statements{$statement_name} = $databases{$database_name}->prepare($statement);	
			};
			if($@){
				&debug('DEBUG', 'ERROR', "Statement failed for $statement_name: $@");
				return 0;
			}
			
			return 1;
		}
		else{
			&debug('DEBUG', 'WARNING', "Unable to add statement $statement_name: statement already exists");
		}
	}
	else{
		&debug('DEBUG', 'WARNING', "Unable to add statement $statement_name: database does not exists");
	}
	
	return 0;
}

sub freeDatabases(){
	foreach my $s (keys(%statements)){
		$statements{$s}->finish();
	}
	
	foreach my $d (keys(%databases)){
		$databases{$d}->disconnect();
	}
}

sub freeStatement()
{
	my $statement = shift;
	
	if(exists($statements{$statement}))
	{
		eval
		{
			$statements{$statement}->finish();
		};
		
		delete($statements{$statement});
	}
}



sub doUpdate(){
	my $handle 		= uc(shift(@_));
	my @arguments 	= @_;
	
	eval{
		$statements{$handle}->execute(@arguments);
	};
	if($@){
		&debug('DEBUG', 'ERROR', "Update error on handle $handle: $@");
		return 0;
	}

	return 1;
}

sub doSelect(){
	my $handle 		= uc(shift(@_));
	my $type 		= uc(shift(@_));
	my @arguments 	= @_;
	
	eval{
		$statements{$handle}->execute(@arguments);
	};
	if($@){
		&debug('DEBUG', 'ERROR', "Select error on handle $handle: $@");
		return undef;
	}
	else{
		if($type eq 'HASHREF_ROW'){
			return ($statements{$handle}->fetchrow_hashref());
		}
		elsif($type eq 'ARRAYREF_ROW'){
			return ($statements{$handle}->fetchrow_arrayref());
		}
		elsif($type eq 'ARRAYREF_ALL'){
			return ($statements{$handle}->fetchall_arrayref());
		}
		elsif($type eq 'HASHREF_ALL'){
			return ($statements{$handle}->fetchall_arrayref({}));
		}
		else{
			return ($statements{$handle}->fetchrow_array());
		}
	}
}

1;
