package Playnet::Personas;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(loadPersona loadPersonaByID getPersona);

my $inited 		= 0;
my %indexes		= ();
my %personas	= ();

sub loadPersona()
{
	&initQueries();
	
	my $source 	= shift(@_);
	my $pindex 	= join('_',@_);
	my $persona = undef;
	
	#print "     P-Index: $pindex\n";
	
	if(!exists($indexes{$pindex}))
	{
		$persona = &doSelect('persona_select', 'hashref_row', @_);
		
		if(defined($persona))
		{
			#print "     Persona ID: ".$persona->{'persona_id'}."\n"; 
			
			$persona->{'sorties'} 		= 0;
			$persona->{'kills'} 		= 0;
			$persona->{'deaths'} 		= 0;
			$persona->{'hits'} 			= 0;
			$persona->{'captures'} 		= 0;
			$persona->{'successes'}		= 0;
			$persona->{'rtbs'} 			= 0;
			$persona->{'rescues'}		= 0;
			$persona->{'mias'} 			= 0;
			$persona->{'kias'} 			= 0;
			$persona->{'tom'} 			= 0;
			
			$indexes{$pindex} = $persona->{'personaid'};
			$personas{$persona->{'personaid'}} 	= $persona;
		}
	}
	else
	{
		$persona = $personas{$indexes{$pindex}};
	}
	
	if(defined($persona))
	{
		if(defined($source))
		{
			$source->{'persona'} = $persona;
		}
		
		return $persona->{'personaid'};
	}
	
	return 0;
}

sub loadPersonaByID()
{
	&initQueries();
	
	my $source 	= shift(@_);
	my $id 		= shift(@_);
	my $persona = undef;
	
	#print "     P-ID: $id\n";
	
	if(!exists($personas{$id}))
	{
		$persona = &doSelect('id_persona_select', 'hashref_row', $id);
		
		if(defined($persona))
		{
			#print "     Persona ID: ".$persona->{'persona_id'}."\n"; 
			
			$persona->{'sorties'} 		= 0;
			$persona->{'kills'} 		= 0;
			$persona->{'deaths'} 		= 0;
			$persona->{'hits'} 			= 0;
			$persona->{'captures'} 		= 0;
			$persona->{'successes'}		= 0;
			$persona->{'rtbs'} 			= 0;
			$persona->{'rescues'}		= 0;
			$persona->{'mias'} 			= 0;
			$persona->{'kias'} 			= 0;
			$persona->{'tom'} 			= 0;
			
			my $pindex  = $persona->{'playerid'} . '_' . $persona->{'countryid'} . '_' . $persona->{'branchid'};
			
			$personas{$id} 		= $persona;
			$indexes{$pindex} 	= $id;
		}
	}
	else
	{
		$persona = $personas{$id};
	}
	
	if(defined($persona))
	{
		if(defined($source))
		{
			$source->{'persona'} = $persona;
		}
		
		return $persona->{'personaid'};
	}
	
	return 0;
}

sub getPersona()
{
	my $pid = shift(@_);
	
	if(exists($personas{$pid}))
	{
		return $personas{$pid};
	}
	
	return undef;
}

sub initQueries()
{
	if(!$inited)
	{
		&addStatement('game_db','persona_select',q{
			SELECT pe.*, pl.*, pe.personaid as persona_id
			FROM wwii_persona pe,
				wwii_player pl
			WHERE pe.playerid = ?
				AND pe.countryid = ?
				AND pe.branchid = ?
				AND pe.playerid = pl.playerid
			LIMIT 1
		});
		
		&addStatement('game_db','id_persona_select',q{
			SELECT pe.*, pl.*, pe.personaid as persona_id
			FROM wwii_persona pe,
				wwii_player pl
			WHERE pe.personaid = ?
				AND pl.playerid = pe.playerid
			LIMIT 1
		});
		
		$inited = 1;
	}
}

1;
