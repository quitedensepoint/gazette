package Playnet::Vehicles;

use strict;

require Exporter;

use vars qw(@ISA @EXPORT);

use lib ('/usr/local/community/scoring');
use Playnet::Database;

@ISA 		= qw(Exporter);
@EXPORT 	= qw(loadVehicle getVehicle);

my $inited 		= 0;
my %indexes		= ();
my %vehicles	= ();
my %stats		= ();

my %blocked		= (
	'1_4_9_4'	=> 'uk_skull',
	'3_4_9_4'	=> 'fr_skull',
	'4_4_9_4'	=> 'de_skull',
	'1_4_9_5'	=> 'uk_buzzard',
	'3_4_9_5'	=> 'fr_buzzard',
	'4_4_9_5'	=> 'de_buzzard'
);

sub loadVehicle()
{
	&initQueries();
	
	my $source 	= shift(@_);
	my $vindex 	= join('_',@_);
	my $vehicle	= undef;
	
	#print "     V-Index: $vindex\n";
	  
	if(exists($blocked{$vindex}))
	{
		return 0;
	}
	
	if(!exists($indexes{$vindex}))
	{
		$vehicle = &doSelect('vehicle_select', 'hashref_row', @_);
		
		if(defined($vehicle))
		{
			$indexes{$vindex} = $vehicle->{'vehicle_id'};
			$vehicles{$vehicle->{'vehicle_id'}} = $vehicle;
		}
		else
		{
			# lets make sure we have it in the scoring db
			
			my $gvehicle = &doSelect('game_vehicle_select', 'hashref_row', @_);
			
			if(defined($gvehicle))
			{
				# lets see if we can translate it?
				
				$vehicle = &doSelect('translation_select', 'hashref_row', $gvehicle->{'vehtype_oid'});
				
				if(!defined($vehicle))
				{
					## add it to gamedb
					
					print "     !!! ADDING SCORING VEHICLE (".$vehicle->{'name'}.") !!!\n";
					
					&doUpdate('vehicle_insert',$gvehicle->{'countryID'},$gvehicle->{'branchid'},$gvehicle->{'categoryID'},$gvehicle->{'classID'},$gvehicle->{'typeID'},$gvehicle->{'fullName'},$gvehicle->{'fullName'});
					
					$vehicle = &doSelect('vehicle_select', 'hashref_row', @_);
					
				}
				else
				{
					print "    !!! USING TRANSLATED VEHICLE (".$gvehicle->{'vehtype_oid'}."->".$vehicle->{'vehicle_id'}.") !!!\n";
				}
				
				if(defined($vehicle))
				{
					$indexes{$vindex} = $vehicle->{'vehicle_id'};
					$vehicles{$vehicle->{'vehicle_id'}} = $vehicle;
				}
			}
		}
	}
	else
	{
		$vehicle = $vehicles{$indexes{$vindex}};
	}
	
	if(defined($vehicle))
	{
		$source->{'vehicle'} = $vehicle;
		return 1;
	}
	
	return 0;
}

sub getVehicle()
{
	my $vid = shift(@_);
	
	if(exists($vehicles{$vid}))
	{
		return $vehicles{$vid};
	}
	
	return undef;
}

sub initQueries()
{
	if(!$inited)
	{
		&addStatement('game_db','game_vehicle_select',q{
			SELECT v.*, c.branchid
			FROM wwii_vehtype v,
				wwii_vehcat c
			WHERE v.countryid = ?
				AND v.categoryid = ?
				AND v.classid = ?
				AND v.typeid = ?
				AND v.categoryid = c.categoryid
			LIMIT 1
		});
		
		&addStatement('community_db','limited_vehicle_select',q{
			SELECT *
			FROM scoring_vehicles
			WHERE country_id = ?
				AND category_id = ?
				AND class_id = ?
				AND type_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','vehicle_select',q{
			SELECT *
			FROM scoring_vehicles
			WHERE country_id = ?
				AND category_id = ?
				AND class_id = ?
				AND type_id = ?
			LIMIT 1
		});
		
		&addStatement('community_db','translation_select',q{
			SELECT v.*
			FROM scoring_vehicle_translations t,
				scoring_vehicles v
			WHERE t.vehicle_oid = ?
				AND t.translated_id = v.vehicle_id
			LIMIT 1
		});
		
		&addStatement('community_db','vehicle_insert',q{
			INSERT INTO scoring_vehicles (vehicle_id,country_id,branch_id,category_id,class_id,type_id,name,short_name,shown,added,modified)
			VALUES (null,?,?,?,?,?,?,?,'true',NOW(),NOW())
		});
		
		$inited = 1;
	}
}

1;
