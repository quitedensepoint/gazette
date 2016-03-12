<?php
/**
 * This allows us to gather all our story files together and load them
 * from one place
 */
require(__DIR__ . "/story-interface.php");
require(__DIR__ . "/StoryBase.php");
require(__DIR__ . "/StoryCampaignStarted.php");
require(__DIR__ . "/StoryCountryCaptured.php");
require(__DIR__ . "/StoryCountryDiminished.php");
require(__DIR__ . "/StoryCountryNoFactoryOutput.php");
require(__DIR__ . "/StoryCycleStarted.php");
require(__DIR__ . "/StoryMajorCityBase.php");
require(__DIR__ . "/StoryMajorCityContested.php");
require(__DIR__ . "/StoryMajorCitySieged.php");
require(__DIR__ . "/StoryMajorCityThreatened.php");
require(__DIR__ . "/StoryVictoryBase.php");
require(__DIR__ . "/StoryVictory.php");
require(__DIR__ . "/StoryVictoryImminent.php");
require(__DIR__ . "/StoryVictoryNear.php");
require(__DIR__ . "/StoryVictoryPossible.php");
require(__DIR__ . "/StoryOffensiveCountry.php");
require(__DIR__ . "/StoryAirfieldsOwned.php");
require(__DIR__ . "/StoryChokepointsOwned.php");
require(__DIR__ . "/StoryBestSortieBase.php");
require(__DIR__ . "/StoryBestRecentSortie.php");
require(__DIR__ . "/StoryBestAirGroundAttack.php");
require(__DIR__ . "/StoryBestAirNavalAttack.php");
require(__DIR__ . "/StoryBestATGunSortie.php");
require(__DIR__ . "/StoryBestATRSortie.php");
require(__DIR__ . "/StoryBestDestroyerSortie.php");
require(__DIR__ . "/StoryBestSapperKillsSortie.php");
require(__DIR__ . "/StoryBestShoreBombardment.php");
require(__DIR__ . "/StoryRDPBase.php");
require(__DIR__ . "/StoryRDPIntro.php");
require(__DIR__ . "/StoryRDPIncrease.php");
require(__DIR__ . "/StoryRDPDecrease.php");
require(__DIR__ . "/StoryRDPPhaseOut.php");
require(__DIR__ . "/StoryRDPRemoval.php");
require(__DIR__ . "/StoryRDPStatus.php");
require(__DIR__ . "/StoryRDPEfficiency.php");
require(__DIR__ . "/StorySpawnLister.php");
require(__DIR__ . "/StoryRDPArchiver.php");
require(__DIR__ . "/StoryFactoryBase.php");
require(__DIR__ . "/StoryFactoryHealth.php");
require(__DIR__ . "/StoryServerNews.php");
require(__DIR__ . "/StoryFactoryOutputShutdown.php");
require(__DIR__ . "/StoryFactoryOutputSlowed.php");
require(__DIR__ . "/StoryFactoryCaptured.php");
require(__DIR__ . "/StoryFactoryUnderAttack.php");
require(__DIR__ . "/StorySortieAverages.php");
require(__DIR__ . "/StoryCasualtyReporter.php");
require(__DIR__ . "/StoryRecentPromotion.php");
require(__DIR__ . "/StoryMisc.php");
require(__DIR__ . "/StoryFrontLines.php");

/**
 * Stories here are skipped as they are possibly not relevant anymore, or may need
 * further work to address correctly
 */
//require(__DIR__ . "/StoryServerStatus.php");
//require(__DIR__ . "/StoryRDPArchiveFiller.php");
//require(__DIR__ . "/StoryMajorCitiesOwned.php");
//require(__DIR__ . "/StoryForcesOnline.php");
