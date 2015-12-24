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
require(__DIR__ . "/StoryMajorCityContested.php");
require(__DIR__ . "/StoryMajorCitySieged.php");
require(__DIR__ . "/StoryMajorCityThreatened.php");
require(__DIR__ . "/StoryVictory.php");
require(__DIR__ . "/StoryVictoryImminent.php");
require(__DIR__ . "/StoryVictoryNear.php");
require(__DIR__ . "/StoryVictoryPossible.php");
