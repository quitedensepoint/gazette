<?php

require(__DIR__ . '/../vendor/autoload.php');

// Chokepoints
use Playnet\WwiiOnline\WwiiOnline\Models\Chokepoint\Bridge;

// Side
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Types\Sapper\British as BritishSapper;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Classes\Para;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Categories\Ground;
use Playnet\WwiiOnline\WwiiOnline\Models\Vehicle\Branches\Air;

echo Bridge::getTypeId();

echo BritishSapper::getTypeId();
echo BritishSapper::getBranchId();
echo BritishSapper::getBranchName();
echo BritishSapper::getCategoryId();
echo BritishSapper::getCategoryName();
echo BritishSapper::getClassId();
echo BritishSapper::getClassName();
echo BritishSapper::getCountryAdjective();
echo BritishSapper::getCountryId();
echo BritishSapper::getCountryName();
echo BritishSapper::getSideAdjective();
echo BritishSapper::getSideId();
echo BritishSapper::getSideKey();
echo BritishSapper::getSideName();
echo BritishSapper::getTypeId();

echo Para::getBranchId();
echo Para::getBranchName();
echo Para::getCategoryId();
echo Para::getCategoryName();
echo Para::getClassId();
echo Para::getClassName();

echo Ground::getBranchId();
echo Ground::getBranchName();
echo Ground::getCategoryId();
echo Ground::getCategoryName();

echo Air::getBranchId();
echo Air::getBranchName();



