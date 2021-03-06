<?php

use Phinx\Migration\AbstractMigration;

class UpdateVehicleNames extends AbstractMigration
{
	public function up()
	{
		// Empties the gazette.vehicles table
		$this->execute("TRUNCATE TABLE `vehicles`;");
		
		// Adds all of the current vehicles with their updated names from community.scoring_vehicles
		$this->execute("INSERT INTO `vehicles` (`vehicle_id`, `country_id`, `branch_id`, `category_id`, `class_id`, `type_id`, `name`, `short_name`, `shown`, `modified`, `added`) VALUES
								(1, 1, 2, 1, 2, 1, 'Spitfire Mk Ia', 'Spit Ia', 'True', '2016-05-11 15:04:08', '2001-09-24 17:30:58'),
								(2, 1, 2, 1, 2, 2, 'Hurricane Mk I', 'Hurri I', 'True', '2004-02-27 20:36:11', '2001-09-24 17:30:58'),
								(4, 1, 1, 2, 4, 1, 'A13 Cruiser Mk II', 'A13', 'True', '2016-05-11 15:04:08', '2001-09-24 17:30:58'),
								(6, 1, 1, 2, 6, 1, 'Bedford OY Truck', 'Bedford', 'True', '2016-05-11 15:04:08', '2001-09-24 17:30:58'),
								(7, 1, 1, 2, 7, 1, 'ROQF 2 Pounder', 'QF 2pdr', 'True', '2016-05-11 15:04:08', '2001-09-24 17:30:58'),
								(8, 1, 1, 4, 9, 1, 'British Rifleman', 'UK Rifleman', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(9, 1, 1, 4, 9, 2, 'British Submachine Gunner', 'UK SMG', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(10, 1, 1, 4, 9, 3, 'British Engineer', 'UK Engineer', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(11, 3, 2, 1, 2, 1, 'Hawk H75 A2-3', 'H75A2-3', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(14, 3, 1, 2, 4, 1, 'Char B1 Bis', 'Char', 'True', '2004-02-27 20:37:48', '2001-09-24 17:30:58'),
								(15, 3, 1, 2, 4, 3, 'Somua S-35', 'S-35', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(17, 3, 1, 2, 7, 1, 'SA mle 1934 (25 mm)', 'Mle 34', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(18, 3, 1, 2, 7, 2, 'SA mle 1937 (47 mm)', 'Mle 37', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(19, 3, 1, 4, 9, 1, 'French Rifleman', 'FR Rifleman', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(20, 3, 1, 4, 9, 2, 'French Submachine Gunner', 'FR SMG', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(21, 3, 1, 4, 9, 3, 'French Engineer', 'FR Engineer', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(22, 4, 2, 1, 1, 1, 'Ju 87B Stuka', 'Ju87', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(23, 4, 2, 1, 2, 1, 'Bf 109E-4', 'Bf109E-4', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(24, 4, 1, 2, 4, 1, 'Panzer III F', 'Pz III F', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(25, 4, 1, 2, 4, 2, 'Panzer 38(t)', 'Pz 38(t)', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(26, 4, 1, 2, 4, 3, 'Panzer II C', 'Pz II C', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(27, 4, 1, 2, 6, 1, 'Opel Blitz Truck', 'Opel', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(28, 4, 1, 2, 6, 2, 'SdKfz 7 Halftrack', 'Halftrack', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(29, 4, 1, 2, 7, 1, 'Pak 36 (37 mm)', 'Pak 36', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(30, 4, 1, 2, 7, 2, 'Flak 36 (88 mm)', 'Flak 36', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(31, 4, 1, 4, 9, 1, 'German Rifleman', 'DE Rifleman', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(32, 4, 1, 4, 9, 2, 'German Submachine Gunner', 'DE SMG', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(33, 4, 1, 4, 9, 3, 'German Engineer', 'DE Engineer', 'True', '2016-05-11 15:04:09', '2001-09-24 17:30:58'),
								(62, 1, 3, 3, 12, 1, 'British Fairmile B', 'UK Fairmile', 'True', '2016-05-11 15:04:09', '2001-10-11 00:55:48'),
								(63, 3, 3, 3, 12, 1, 'French Fairmile B', 'FR Fairmile', 'True', '2016-05-11 15:04:09', '2001-10-11 00:56:22'),
								(64, 4, 3, 3, 12, 1, 'German Fairmile B', 'DE Fairmile', 'True', '2016-05-11 15:04:09', '2001-10-11 00:56:26'),
								(65, 1, 2, 1, 1, 1, 'British Blenheim Mk IV', 'UK Blen IV', 'True', '2016-05-11 15:04:10', '2001-10-17 16:26:34'),
								(66, 4, 2, 1, 2, 2, 'Bf 110C-4', 'Bf110C-4', 'True', '2016-05-11 15:04:10', '2001-10-17 16:27:01'),
								(67, 3, 1, 2, 4, 2, 'Renault R-35', 'R-35', 'True', '2016-05-11 15:04:10', '2001-10-23 14:38:18'),
								(70, 3, 2, 1, 1, 1, 'French Blenheim Mk IV', 'FR Blen IV', 'True', '2016-05-11 15:04:10', '2001-10-23 14:40:37'),
								(71, 4, 1, 2, 7, 3, 'Flak 30 (20 mm)', 'Flak 30', 'True', '2016-05-11 15:04:10', '2002-01-07 22:39:56'),
								(72, 1, 2, 1, 2, 3, 'Blenheim Mk If', 'Blen I', 'True', '2016-05-11 15:04:10', '2002-02-04 13:56:03'),
								(73, 1, 1, 2, 4, 4, 'Matilda Mk II', 'Matilda', 'True', '2016-05-11 15:04:10', '2002-03-04 17:07:29'),
								(74, 4, 1, 2, 4, 4, 'StuG III B', 'StuG III B', 'True', '2016-05-11 15:04:10', '2002-03-04 17:08:33'),
								(75, 4, 1, 2, 4, 5, 'Panzer IV D', 'Pz IV D', 'True', '2016-05-11 15:04:10', '2002-06-18 17:20:47'),
								(76, 4, 2, 1, 1, 2, 'He 111H-2', 'Heinkel', 'True', '2016-05-11 15:04:10', '2002-06-18 17:22:19'),
								(77, 3, 1, 2, 6, 2, 'Laffly S-20 Truck', 'Laffly S-20', 'True', '2016-05-11 15:04:10', '2002-06-18 17:23:38'),
								(78, 3, 1, 2, 7, 3, 'French CA mle 38 (25 mm)', 'FR Mle 38', 'True', '2016-05-11 15:04:10', '2002-06-18 17:24:54'),
								(79, 1, 1, 2, 7, 3, 'British CA mle 38 (25 mm)', 'UK Mle 38', 'True', '2016-05-11 15:04:10', '2002-06-18 17:25:49'),
								(80, 4, 3, 3, 13, 1, 'German Type 1934 (Destroyer)', 'DE Destroyer', 'True', '2016-05-11 15:04:10', '2002-12-19 15:32:09'),
								(81, 3, 1, 2, 4, 4, 'Panhard AMD 178', 'Panhard', 'True', '2004-02-27 20:37:59', '2002-12-19 15:32:22'),
								(82, 4, 1, 2, 4, 7, 'SdKfz 232', 'SdKfz 232', 'True', '2004-02-27 20:37:59', '2002-12-19 15:32:39'),
								(83, 1, 1, 2, 6, 2, 'British Morris CDSW Gun Tractor', 'UK Morris', 'True', '2016-05-11 15:04:10', '2002-12-19 15:32:48'),
								(84, 1, 1, 2, 4, 5, 'British Vickers Mk IV', 'UK Vickers', 'True', '2016-05-11 15:04:10', '2002-12-19 15:32:55'),
								(85, 1, 1, 2, 7, 4, 'British Bofors (40 mm)', 'UK Bofors', 'True', '2016-05-11 15:04:10', '2002-12-19 15:33:02'),
								(86, 3, 2, 1, 2, 4, 'Dewoitine D.520', 'D.520', 'True', '2004-03-01 17:40:59', '2002-12-19 15:36:49'),
								(87, 1, 1, 2, 4, 7, 'Crusader Mk II', 'Crusader II', 'True', '2016-05-11 15:04:11', '2003-03-21 16:42:06'),
								(88, 4, 1, 2, 4, 6, 'Panzer III H', 'Pz III H', 'True', '2016-05-11 15:04:11', '2003-03-21 16:42:15'),
								(89, 1, 1, 2, 4, 6, 'Daimler Mk I', 'Daimler', 'True', '2016-05-11 15:04:11', '2003-03-21 16:42:25'),
								(90, 1, 2, 1, 2, 4, 'Spitfire Mk Vb', 'Spit Vb', 'True', '2016-05-11 15:04:11', '2003-03-21 16:42:36'),
								(91, 1, 2, 1, 2, 5, 'Hurricane Mk IIc', 'Hurri IIc', 'True', '2016-05-11 15:04:11', '2003-03-21 16:42:46'),
								(92, 4, 2, 1, 2, 3, 'Bf 109F-4', 'Bf109F-4', 'True', '2016-05-11 15:04:11', '2003-03-21 16:42:55'),
								(94, 3, 2, 1, 2, 5, 'Hawk H81A-2', 'H81A-2', 'True', '2016-05-11 15:04:11', '2003-04-18 10:16:31'),
								(95, 3, 2, 1, 1, 2, 'DB-7', 'DB-7', 'True', '2016-05-11 15:04:11', '2003-07-29 13:40:14'),
								(96, 3, 1, 2, 4, 5, 'H-39', 'H-39', 'True', '2016-05-11 15:04:11', '2003-07-29 13:40:38'),
								(97, 1, 2, 1, 1, 3, 'Havoc Mk I', 'Havoc', 'True', '2016-05-11 15:04:11', '2003-07-29 13:40:58'),
								(98, 1, 3, 3, 13, 1, 'British Type 1934 (Destroyer)', 'UK Destroyer', 'True', '2016-05-11 15:04:11', '2003-08-28 12:40:32'),
								(99, 3, 3, 3, 13, 1, 'French Type 1934 (Destroyer)', 'FR Destroyer', 'True', '2016-05-11 15:04:11', '2003-08-28 12:40:49'),
								(100, 1, 3, 3, 13, 2, 'British Type A-1917 (Freighter)', 'UK Freighter', 'True', '2016-05-11 15:04:11', '2003-10-10 11:52:43'),
								(101, 3, 3, 3, 13, 2, 'French Type A-1917 (Freighter)', 'FR Freighter', 'True', '2016-05-11 15:04:11', '2003-10-10 11:53:00'),
								(102, 4, 3, 3, 13, 2, 'German Type A-1917 (Freighter)', 'DE Freighter', 'True', '2016-05-11 15:04:11', '2003-10-10 11:53:16'),
								(103, 4, 1, 2, 6, 3, 'SdKfz 251 C', 'SdKfz 251 C', 'True', '2016-05-11 15:04:11', '2003-10-17 11:34:25'),
								(104, 1, 1, 4, 9, 6, 'British Light Machine Gunner', 'UK LMG', 'True', '2016-05-11 15:04:11', '2003-10-29 12:42:01'),
								(105, 3, 1, 4, 9, 6, 'French Light Machine Gunner', 'FR LMG', 'True', '2016-05-11 15:04:11', '2003-10-29 12:42:08'),
								(106, 4, 1, 4, 9, 6, 'German Light Machine Gunner', 'DE LMG', 'True', '2016-05-11 15:04:11', '2003-10-29 12:42:14'),
								(107, 3, 1, 2, 4, 6, 'French M3A3 Stuart', 'FR Stuart', 'True', '2016-05-11 15:04:11', '2003-10-29 12:42:20'),
								(108, 3, 2, 1, 2, 6, 'Bell mle 14a', 'Bell 14a', 'True', '2016-05-11 15:04:11', '2003-12-22 13:05:41'),
								(109, 3, 1, 2, 7, 4, 'French Bofors (40 mm)', 'FR Bofors', 'True', '2016-05-11 15:04:11', '2003-12-22 13:06:05'),
								(110, 3, 1, 2, 6, 3, 'Laffly W15 TCC', 'Laffly W15', 'True', '2016-05-11 15:04:11', '2003-12-22 13:06:24'),
								(111, 4, 1, 2, 7, 4, 'Flak 28 (40 mm)', 'Flak 28', 'True', '2016-05-11 15:04:11', '2003-12-22 13:06:41'),
								(112, 1, 1, 4, 9, 7, 'British Grenadier', 'UK Grenadier', 'True', '2016-05-11 15:04:11', '2004-03-01 12:41:52'),
								(113, 3, 1, 4, 9, 7, 'French Grenadier', 'FR Grenadier', 'True', '2016-05-11 15:04:11', '2004-03-01 12:42:24'),
								(114, 4, 1, 4, 9, 7, 'German Grenadier', 'DE Grenadier', 'True', '2016-05-11 15:04:12', '2004-03-01 12:42:54'),
								(120, 1, 1, 4, 9, 9, 'British Anti-Tank Rifleman', 'UK ATR', 'True', '2016-05-11 15:04:12', '2004-07-22 14:12:07'),
								(121, 3, 1, 4, 9, 9, 'French Anti-Tank Rifleman', 'FR ATR', 'True', '2016-05-11 15:04:12', '2004-07-22 14:17:37'),
								(122, 4, 1, 4, 9, 9, 'German Anti-Tank Rifleman', 'DE ATR', 'True', '2016-05-11 15:04:12', '2004-07-22 14:34:24'),
								(123, 1, 1, 2, 7, 5, 'ROQF 6 Pounder (57 mm)', 'QF 6pdr', 'True', '2016-05-11 15:04:12', '2004-10-11 12:45:20'),
								(124, 3, 1, 2, 7, 5, 'French M1A3 (57 mm)', 'FR M1', 'True', '2016-05-11 15:04:12', '2004-10-11 16:17:29'),
								(125, 4, 1, 2, 7, 5, 'Pak 38 (50 mm)', 'Pak 38', 'True', '2016-05-11 15:04:12', '2004-10-11 16:17:53'),
								(126, 1, 1, 2, 4, 8, 'Crusader Mk III', 'Crusader III', 'True', '2016-05-11 15:04:12', '2004-11-02 13:16:38'),
								(127, 4, 1, 2, 4, 9, 'StuG III G', 'StuG III G', 'True', '2016-05-11 15:04:12', '2004-11-18 16:54:29'),
								(128, 4, 1, 2, 4, 8, 'Panzer IV G', 'Pz IV G', 'True', '2016-05-11 15:04:12', '2004-11-24 21:33:12'),
								(129, 3, 1, 2, 4, 7, 'French M4A2 Sherman', 'FR Sherman', 'True', '2016-05-11 15:04:12', '2004-12-03 11:18:11'),
								(131, 3, 2, 1, 2, 7, 'Mle 322-15 (P-38F)', 'FR P-38F', 'True', '2016-05-11 15:04:12', '2004-12-21 16:43:13'),
								(132, 4, 2, 1, 1, 5, 'German Ju 52-3M', 'DE Ju52', 'True', '2016-05-11 15:04:12', '2004-12-21 16:43:13'),
								(133, 3, 2, 1, 1, 3, 'French Ju 52-3M', 'FR Ju52', 'True', '2016-05-11 15:04:12', '2004-12-21 16:43:14'),
								(136, 1, 2, 1, 1, 4, 'C-47 Dakota', 'C-47', 'True', '2016-05-11 15:04:12', '2004-12-21 16:43:20'),
								(137, 4, 2, 1, 2, 4, 'Fw 190A-4', 'Fw190A-4', 'True', '2016-05-11 15:04:12', '2004-12-21 16:43:43'),
								(138, 1, 2, 1, 2, 6, 'Spitfire Mk IXc', 'Spitfire IXc', 'True', '2016-05-11 15:04:12', '2004-12-21 20:47:29'),
								(139, 1, 2, 1, 1, 2, 'British Blenheim IV-T', 'UK Blen IV-T', 'True', '2016-05-11 15:04:12', '2005-03-04 10:52:17'),
								(140, 4, 2, 1, 1, 4, 'German Blenheim IV-T', 'DE Blen IV-T', 'True', '2016-05-11 15:04:12', '2005-03-04 10:52:17'),
								(144, 4, 1, 2, 4, 10, 'Panzer VI E Tiger', 'Tiger', 'True', '2016-05-11 15:04:12', '2005-10-26 12:10:08'),
								(146, 1, 1, 2, 4, 9, 'Churchill Mk VII', 'Churchill VII', 'True', '2016-05-11 15:04:12', '2005-11-07 22:58:17'),
								(147, 3, 1, 2, 4, 8, 'French M10 Wolverine', 'FR M10', 'True', '2016-05-11 15:04:13', '2005-11-14 22:23:16'),
								(149, 3, 1, 4, 9, 12, 'French Sniper', 'FR Sniper', 'True', '2016-05-11 15:04:13', '2005-12-28 12:40:17'),
								(150, 4, 1, 4, 9, 12, 'German Sniper', 'DE Sniper', 'True', '2016-05-11 15:04:13', '2005-12-28 12:40:17'),
								(151, 1, 1, 4, 9, 12, 'British Sniper', 'UK Sniper', 'True', '2016-05-11 15:04:13', '2005-12-28 12:40:17'),
								(152, 1, 1, 2, 4, 10, 'Churchill Mk III', 'Churchill III', 'True', '2016-05-11 15:04:13', '2006-01-01 16:05:20'),
								(153, 3, 1, 2, 4, 9, 'French M4A3 Sherman 76', 'FR Sherman 76', 'True', '2016-05-11 15:04:13', '2006-10-05 11:57:00'),
								(154, 1, 1, 2, 7, 6, 'ROQF 17 Pounder (76.2 mm)', 'QF 17pdr', 'True', '2016-05-11 15:04:13', '2006-12-21 18:39:47'),
								(155, 4, 1, 2, 7, 6, 'Pak 40 (75 mm)', 'Pak 40', 'True', '2016-05-11 15:04:13', '2006-12-21 18:40:40'),
								(156, 3, 1, 2, 7, 6, 'M5A2 (76.2 mm)', 'FR M5', 'True', '2016-05-11 15:04:13', '2006-12-21 18:40:59'),
								(157, 4, 2, 1, 2, 5, 'Bf 109G-6', 'Bf109G-6', 'True', '2016-05-11 15:04:13', '2007-07-03 14:41:43'),
								(158, 3, 2, 1, 2, 8, 'Hawk H87B-3', 'H87B-3', 'True', '2016-05-11 15:04:13', '2007-07-03 14:41:43'),
								(159, 1, 2, 1, 2, 7, 'Hurricane Mk IIb', 'Hurri IIb', 'True', '2016-05-11 15:04:13', '2007-07-03 14:41:43'),
								(160, 4, 1, 4, 9, 13, 'German Light Mortarman', 'DE Lt Mortar', 'True', '2016-05-11 15:04:13', '2007-09-10 10:44:01'),
								(161, 3, 1, 4, 9, 13, 'French Light Mortarman', 'FR Lt Mortar', 'True', '2016-05-11 15:04:13', '2007-09-10 10:44:01'),
								(162, 1, 1, 4, 9, 13, 'British Light Mortarman', 'UK Lt Mortar', 'True', '2016-05-11 15:04:13', '2007-09-10 10:44:03'),
								(163, 1, 1, 4, 14, 1, 'British Airborne Rifle', 'UK Para Rifle', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:12'),
								(164, 1, 1, 4, 14, 2, 'British Airborne Submachine Gunner', 'UK Para SMG', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:35'),
								(165, 1, 1, 4, 14, 6, 'British Airborne Light Machine Gunner', 'UK Para LMG', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:37'),
								(166, 1, 1, 4, 14, 9, 'British Airborne Anti-Tank Rifleman', 'UK Para ATR', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:38'),
								(167, 1, 1, 4, 14, 12, 'British Airborne Sniper', 'UK Para Sniper', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:39'),
								(168, 1, 1, 4, 14, 13, 'British Airborne Mortar', 'UK Para Mortar', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:40'),
								(169, 3, 1, 4, 14, 1, 'French Airborne Rifle', 'FR Para Rifle', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:51'),
								(170, 3, 1, 4, 14, 2, 'French Airborne Submachine Gunner', 'FR Para SMG', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:52'),
								(171, 3, 1, 4, 14, 6, 'French Airborne Light Machine Gunner', 'FR Para LMG', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:53'),
								(172, 3, 1, 4, 14, 9, 'French Airborne Anti-Tank Rifleman', 'FR Para ATR', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:55'),
								(173, 3, 1, 4, 14, 12, 'French Airborne Sniper', 'FR Para Sniper', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:56'),
								(174, 3, 1, 4, 14, 13, 'French Airborne Mortar', 'FR Para Mortar', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:57'),
								(175, 4, 1, 4, 14, 1, 'German Airborne Rifle', 'DE Para Rifle', 'True', '2016-05-11 15:04:13', '2008-01-07 14:48:59'),
								(176, 4, 1, 4, 14, 2, 'German Airborne Submachine Gunner', 'DE Para SMG', 'True', '2016-05-11 15:04:13', '2008-01-07 14:49:00'),
								(177, 4, 1, 4, 14, 6, 'German Airborne Light Machine Gunner', 'DE Para LMG', 'True', '2016-05-11 15:04:13', '2008-01-07 14:49:01'),
								(178, 4, 1, 4, 14, 9, 'German Airborne Anti-Tank Rifleman', 'DE Para ATR', 'True', '2016-05-11 15:04:14', '2008-01-07 14:49:02'),
								(179, 4, 1, 4, 14, 12, 'German Airborne Sniper', 'DE Para Sniper', 'True', '2016-05-11 15:04:14', '2008-01-07 14:49:03'),
								(180, 4, 1, 4, 14, 13, 'German Airborne Mortar', 'DE Para Mortar', 'True', '2016-05-11 15:04:14', '2008-01-07 14:49:04'),
								(181, 4, 2, 1, 2, 6, 'Bf 109E-1', 'Bf109E-1', 'True', '2016-05-11 15:04:14', '2008-11-25 18:29:17'),
								(183, 4, 2, 1, 2, 8, 'Bf 110F-B', 'Bf110F-B', 'True', '2016-05-11 15:04:14', '2009-02-11 12:00:00'),
								(185, 1, 2, 1, 2, 9, 'Spitfire Mk IIb', 'Spit IIb', 'True', '2016-05-11 15:04:14', '2009-05-05 16:08:16'),
								(186, 4, 2, 1, 2, 9, 'Bf 109F-2', 'Bf109F-2', 'True', '2016-05-11 15:04:14', '2010-07-15 15:47:32'),
								(188, 2, 1, 4, 9, 1, 'American Rifleman', 'US Rifleman', 'True', '2016-05-11 15:04:14', '2012-01-04 12:52:49'),
								(189, 2, 1, 4, 9, 14, 'American Semi-Auto Rifleman', 'US Semi-Auto', 'True', '2016-05-11 15:04:14', '2012-01-22 17:47:03'),
								(191, 2, 1, 4, 9, 6, 'American Automatic Rifleman', 'US Auto Rifleman', 'True', '2016-05-11 15:04:14', '2012-01-22 17:54:08'),
								(193, 2, 1, 4, 9, 15, 'American Anti-Tank Soldier', 'US ATS', 'True', '2016-05-11 15:04:14', '2012-01-22 18:11:53'),
								(194, 4, 1, 4, 9, 14, 'German Semi-Auto Rifleman', 'DE Semi-Auto', 'True', '2016-05-12 01:32:16', '2012-01-27 13:53:59'),
								(195, 4, 1, 4, 9, 15, 'German Anti-Tank Soldier', 'DE ATS', 'True', '2016-05-11 15:04:14', '2012-01-27 13:55:03'),
								(196, 3, 1, 4, 9, 15, 'French Anti-Tank Soldier', 'FR ATS', 'True', '2016-05-11 15:04:14', '2012-02-08 11:33:05'),
								(197, 1, 1, 4, 9, 15, 'British Anti-Tank Soldier', 'UK ATS', 'True', '2016-05-11 15:04:14', '2012-02-08 11:49:13'),
								(198, 2, 1, 2, 4, 3, 'American M4A3 Sherman 76', 'US Sherman 76', 'True', '2016-05-11 15:04:14', '2012-02-10 16:39:23'),
								(199, 2, 1, 4, 9, 2, 'American Submachine Gunner', 'US SMG', 'True', '2016-05-11 15:04:14', '2012-02-10 16:50:24'),
								(200, 2, 1, 2, 4, 1, 'American M4A2 Sherman', 'US Sherman', 'True', '2016-05-11 15:04:14', '2012-02-10 16:50:50'),
								(201, 2, 1, 2, 7, 1, 'American Bofors (40 mm)', 'US Bofors', 'True', '2016-05-11 15:04:15', '2012-02-10 16:52:19'),
								(202, 2, 1, 2, 7, 3, 'American M5 (76.2 mm)', 'US M5', 'True', '2016-05-11 15:04:15', '2012-02-10 16:56:24'),
								(203, 2, 1, 4, 9, 12, 'American Sniper', 'US Sniper', 'True', '2016-05-11 15:04:15', '2012-02-10 16:57:01'),
								(204, 2, 2, 1, 2, 1, 'P-38F Lightning', 'US P-38F', 'True', '2016-05-11 15:04:15', '2012-02-10 17:03:36'),
								(205, 2, 1, 4, 9, 3, 'American Engineer', 'US Engineer', 'True', '2016-05-11 15:04:15', '2012-02-10 17:08:23'),
								(206, 2, 1, 2, 4, 2, 'American M10 Wolverine', 'US M10', 'True', '2016-05-11 15:04:15', '2012-02-10 17:09:32'),
								(207, 2, 1, 2, 7, 2, 'American M1 (57 mm)', 'US M1', 'True', '2016-05-11 15:04:15', '2012-02-10 17:20:55'),
								(249, 3, 2, 1, 1, 4, 'French Blenheim IV-T', 'FR Blen IV-T', 'True', '2016-05-11 15:04:15', '2014-10-01 16:11:21'),
								(250, 2, 1, 2, 4, 4, 'American M3A3 Stuart', 'US Stuart', 'True', '2016-05-11 15:04:16', '2014-10-01 16:11:21'),
								(256, 2, 1, 2, 4, 5, 'American Vickers Mk IV', 'US Vickers', 'True', '2016-05-11 15:04:16', '2015-09-03 19:30:05'),
								(257, 2, 1, 2, 4, 6, 'American Daimler Mk I', 'US Daimler', 'True', '2016-05-11 15:04:16', '2015-09-03 19:30:06'),
								(258, 2, 1, 2, 6, 2, 'American Morris CDSW Gun Tractor', 'US Morris', 'True', '2016-05-11 15:04:16', '2015-09-03 19:30:06'),
								(259, 2, 1, 2, 7, 4, 'American CA mle 38 (25 mm)', 'US Mle 38', 'True', '2016-05-11 15:04:16', '2015-09-03 19:30:06'),
								(260, 2, 1, 4, 9, 9, 'American Anti-Tank Rifleman', 'US ATR', 'True', '2016-05-11 15:04:16', '2015-09-03 19:30:06'),
								(261, 2, 1, 4, 9, 13, 'American Light Mortarman', 'US Lt Mortar', 'True', '2016-05-11 15:04:16', '2015-09-03 19:30:06'),
								(262, 3, 1, 4, 9, 14, 'French Semi-Auto', 'FR Semi-Auto', 'True', '2016-02-01 03:19:27', '2015-10-12 17:00:03'),
								(263, 4, 1, 4, 9, 16, 'German Gewehr 41 (W)', 'G41(W)', 'True', '2016-05-11 15:04:16', '2015-10-12 17:00:03'),
								(265, 3, 1, 4, 9, 16, 'French Sapper', 'FR Sapper', 'True', '2016-02-01 03:19:27', '2015-12-19 01:40:03'),
								(266, 1, 1, 4, 9, 16, 'British Sapper', 'UK Sapper', 'True', '2016-02-01 03:19:27', '2015-12-19 02:15:04'),
								(267, 4, 1, 4, 9, 17, 'German Sapper', 'DE Sapper', 'True', '2016-02-01 03:19:27', '2015-12-19 02:15:05'),
								(268, 2, 1, 4, 9, 17, 'American M3A1 Submachine Gunner', 'US M3A1 SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 02:15:05'),
								(269, 3, 1, 4, 9, 17, 'French M3A1 Submachine Gunner', 'FR M3A1 SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 02:15:05'),
								(270, 2, 1, 4, 9, 18, 'American Limited M3A1 Submachine Gunner', 'US Ltd M3A1 SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 02:15:06'),
								(271, 3, 1, 4, 9, 18, 'French Limited M3A1 Submachine Gunner', 'FR Ltd M3A1 SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 02:25:03'),
								(272, 1, 1, 4, 9, 17, 'British Sten Mk II Submachine Gunner', 'UK Sten SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 02:30:04'),
								(273, 1, 1, 4, 9, 18, 'British Limited Sten Mk II Submachine Gunner', 'UK Ltd Sten SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 02:30:04'),
								(274, 2, 1, 4, 9, 16, 'American Sapper', 'US Sapper', 'True', '2016-05-11 15:04:16', '2015-12-19 02:45:04'),
								(275, 4, 1, 4, 9, 18, 'German MP34 Submachine Gunner', 'DE MP34 SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 03:10:04'),
								(276, 4, 1, 4, 9, 19, 'German Limited MP34 Submachine Gunner', 'DE Ltd MP34 SMG', 'True', '2016-05-11 15:04:16', '2015-12-19 03:10:04'),
								(277, 4, 1, 4, 9, 20, 'German Ground FG42G', 'DE Ground FG42', 'True', '2016-05-11 15:04:16', '2015-12-19 03:10:04'),
								(279, 4, 1, 4, 14, 14, 'German Para FG42G', 'DE Para FG42', 'True', '2016-02-01 03:19:27', '2015-12-24 21:45:04');
		");        
	}
	
	public function down()
	{
		$this->execute("INSERT INTO `vehicles` (`vehicle_id`, `country_id`, `branch_id`, `category_id`, `class_id`, `type_id`, `name`, `short_name`, `spawns`, `base_spawns`, `shown`, `added`, `modified`) VALUES
								(1, 1, 2, 1, 2, 1, 'Spitfire MkI', 'Spit MkI', 0, 0, 'True', '2003-06-14 14:19:43', '2014-05-19 17:35:26'),
								(2, 1, 2, 1, 2, 2, 'Hurricane MkI', 'Hurri MkI', 60, 60, 'True', '2003-06-14 14:19:43', '2014-05-19 17:35:26'),
								(4, 1, 1, 2, 4, 1, 'Cruiser A13 Tank', 'A13', 12, 12, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(6, 1, 1, 2, 6, 1, 'Bedford OY 4x2', 'Bedford', 24, 24, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(7, 1, 1, 2, 7, 1, 'QF 2 LBer AT Gun', '2pdr', 24, 24, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(8, 1, 1, 4, 9, 1, 'Rifleman', 'Rifleman', 400, 400, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(9, 1, 1, 4, 9, 2, 'SMG', 'SMG', 100, 100, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(10, 1, 1, 4, 9, 3, 'Sapper', 'Sapper', 50, 50, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(11, 3, 2, 1, 2, 1, 'Curtiss Hawk 75 A2', 'Hawk 75', 60, 60, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(14, 3, 1, 2, 4, 1, 'Char B1 Bis Tank', 'Char B1', 3, 3, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(15, 3, 1, 2, 4, 3, 'Somua S35 Tank', 'S35', 5, 5, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(17, 3, 1, 2, 7, 1, 'SA-L Mle 1934 AT Gun', 'Mle 1934', 18, 18, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(18, 3, 1, 2, 7, 2, 'SA Mle 1937 AT Gun', 'Mle 1937', 6, 6, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(19, 3, 1, 4, 9, 1, 'Rifleman', 'Rifleman', 400, 400, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(20, 3, 1, 4, 9, 2, 'SMG', 'SMG', 100, 100, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(21, 3, 1, 4, 9, 3, 'Sapper', 'Sapper', 50, 50, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(22, 4, 2, 1, 1, 1, 'Ju87B Stuka', 'Stuka', 25, 25, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(23, 4, 2, 1, 2, 1, 'Bf109E-4', '109E', 0, 0, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(24, 4, 1, 2, 4, 1, 'PzKpfw III F Tank', 'PzIIIF', 3, 3, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(25, 4, 1, 2, 4, 2, 'Pz 38(t) Tank', 'Pz38t', 5, 5, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(26, 4, 1, 2, 4, 3, 'PzKpfw IIc Tank', 'PzIIc', 4, 4, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(27, 4, 1, 2, 6, 1, 'Opel Blitz', 'Opel', 24, 24, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(28, 4, 1, 2, 6, 2, 'SdKfz 7 Halftrack', 'SdKfz 7', 6, 6, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(29, 4, 1, 2, 7, 1, 'Pak 36 37mm AT Gun', 'Pak 36', 18, 18, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(30, 4, 1, 2, 7, 2, 'Flak 36 88mm AT Gun', 'Flak 36', 6, 6, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(31, 4, 1, 4, 9, 1, 'Rifleman', 'Rifleman', 400, 400, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(32, 4, 1, 4, 9, 2, 'SMG', 'SMG', 100, 100, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(33, 4, 1, 4, 9, 3, 'Sapper', 'Sapper', 50, 50, 'False', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(62, 1, 3, 3, 12, 1, 'Fairmile Model B', 'Fairmile', 19, 19, 'True', '2003-06-14 14:19:44', '2014-05-19 17:35:26'),
								(63, 3, 3, 3, 12, 1, 'French Fairmile Model B', 'Fairmile', 19, 19, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(64, 4, 3, 3, 12, 1, 'Captured Fairmile Model B', 'Fairmile', 10, 10, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(65, 1, 2, 1, 1, 1, 'Blenheim Mk IV Medium Bomber', 'Blen IV', 25, 25, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(66, 4, 2, 1, 2, 2, 'Bf110c', 'Bf110c', 60, 60, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(67, 3, 1, 2, 4, 2, 'Renault R35 Tank', 'R35', 3, 3, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(70, 3, 2, 1, 1, 1, 'French Blenheim Mk IV', 'Blen IV', 25, 25, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(71, 4, 1, 2, 7, 3, 'Flak 30 20mm AA Gun', 'Flak 30', 18, 18, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(72, 1, 2, 1, 2, 3, 'Blenheim Mk I Fighter-Bomber', 'Blen I', 5, 5, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(73, 1, 1, 2, 4, 4, 'Matilda MkII', 'Matilda', 2, 2, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(74, 4, 1, 2, 4, 4, 'StuG IIIB', 'Stug III', 2, 2, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(75, 4, 1, 2, 4, 5, 'PzKpfw IV D Tank', 'PzIVD', 2, 2, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(76, 4, 2, 1, 1, 2, 'Heinkel 111 Medium Bomber', 'He-111', 15, 15, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(77, 3, 1, 2, 6, 2, 'Laffly S-20', 'Laffly', 30, 30, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(78, 3, 1, 2, 7, 3, 'French CA mle 38', 'Mle 38', 18, 18, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(79, 1, 1, 2, 7, 3, 'British CA mle 38 AA Gun', 'Mle 38', 18, 18, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(80, 4, 3, 3, 13, 1, 'Type 1934', 'Z34', 8, 8, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(81, 3, 1, 2, 4, 4, 'Panhard AMD 178', 'Panhard', 6, 6, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(82, 4, 1, 2, 4, 7, 'SdKfz 232 Armored Car', 'SdKfz 232', 6, 6, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(83, 1, 1, 2, 6, 2, 'Morris CDSW', 'Morris', 6, 6, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(84, 1, 1, 2, 4, 5, 'Vickers MkII', 'Vickers', 9, 9, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(85, 1, 1, 2, 7, 4, 'Bofors 40mm AA Gun', 'Bofors', 8, 8, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(86, 3, 2, 1, 2, 4, 'Dewoitine D.520', 'D520', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(87, 1, 1, 2, 4, 7, 'Crusader MkII', 'Crusader', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(88, 4, 1, 2, 4, 6, 'PzKpfw III Ausf H', 'PzIIIH', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(89, 1, 1, 2, 4, 6, 'Daimler Mk1', 'Daimler', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(90, 1, 2, 1, 2, 4, 'Spitfire MkVb', 'Spit MkVb', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(91, 1, 2, 1, 2, 5, 'Hurricane MkIIc', 'Hurri MkII', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(92, 4, 2, 1, 2, 3, 'Bf-109F4', '109F', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(93, 4, 2, 1, 1, 3, 'Bf-110C4/B Fighter-Bomber', 'Bf-110C4/B', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(94, 3, 2, 1, 2, 5, 'Curtiss H81-a2', 'H81', 0, 0, 'True', '2003-06-14 14:19:45', '2014-05-19 17:35:26'),
								(95, 3, 2, 1, 1, 2, 'Douglas DB-7', 'DB7', 15, 15, 'True', '2003-07-29 13:46:50', '2014-05-19 17:35:26'),
								(96, 3, 1, 2, 4, 5, 'Hotchkiss H39', 'H39', 5, 5, 'True', '2003-07-29 13:47:11', '2014-05-19 17:35:26'),
								(97, 1, 2, 1, 1, 3, 'Douglas Havoc I', 'Havoc I', 15, 15, 'True', '2003-07-29 13:48:19', '2014-05-19 17:35:26'),
								(98, 1, 3, 3, 13, 1, 'British Destroyer', 'British DD', 8, 8, 'True', '2003-08-28 12:44:26', '2014-05-19 17:35:26'),
								(99, 3, 3, 3, 13, 1, 'French Destroyer', 'French DD', 8, 8, 'True', '2003-08-28 12:45:18', '2014-05-19 17:35:26'),
								(100, 1, 3, 3, 13, 2, 'British Freighter', 'UK Freight', 8, 8, 'True', '2003-10-29 12:57:54', '2014-05-19 17:35:26'),
								(101, 3, 3, 3, 13, 2, 'French Freighter', 'FR Freight', 8, 8, 'True', '2003-10-29 12:57:58', '2014-05-19 17:35:26'),
								(102, 4, 3, 3, 13, 2, 'German Freighter', 'DE Freight', 8, 8, 'True', '2003-10-29 12:58:01', '2014-05-19 17:35:26'),
								(103, 1, 1, 4, 9, 6, 'LMG', 'LMG', 25, 25, 'False', '2003-10-29 12:58:03', '2014-05-19 17:35:26'),
								(104, 3, 1, 4, 9, 6, 'LMG', 'LMG', 25, 25, 'False', '2003-10-29 12:58:06', '2014-05-19 17:35:26'),
								(105, 4, 1, 4, 9, 6, 'LMG', 'LMG', 25, 25, 'False', '2003-10-29 12:58:08', '2014-05-19 17:35:26'),
								(106, 4, 1, 2, 6, 3, 'Sdkfz 251c', '251', 0, 0, 'True', '2003-10-29 12:58:10', '2014-05-19 17:35:26'),
								(107, 3, 1, 2, 4, 6, 'Stuart M3A3', 'Stuart', 0, 0, 'True', '2003-10-29 12:58:13', '2014-05-19 17:35:26'),
								(108, 3, 2, 1, 2, 6, 'Bell 14a', 'Bell 14a', 0, 0, 'True', '2004-01-07 11:20:50', '2014-05-19 17:35:26'),
								(109, 3, 1, 2, 7, 4, 'FR Bofors 40mm', 'FR Bofors', 8, 8, 'True', '2004-01-07 11:22:42', '2014-05-19 17:35:26'),
								(110, 3, 1, 2, 6, 3, 'Laffly W15', 'Laffly W15', 0, 0, 'True', '2004-01-07 11:23:25', '2014-05-19 17:35:26'),
								(111, 4, 1, 2, 7, 4, '4cm Flak28', '4cm Flak28', 8, 8, 'True', '2004-01-07 11:23:54', '2014-05-19 17:35:26'),
								(112, 1, 1, 2, 4, 2, 'Renault R35', 'R35', 0, 0, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(113, 3, 1, 2, 6, 1, 'Bedford OY 4x2', 'Bedford', 0, 0, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(114, 1, 1, 2, 4, 3, 'Char B1 Bis', 'Char', 0, 0, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(115, 1, 1, 2, 7, 2, 'SA-L Mle 1934', 'Mle 1934', 0, 0, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(119, 1, 1, 4, 9, 7, 'British Grenadier', 'Grenadier', 25, 25, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(120, 3, 1, 4, 9, 7, 'French Grenadier', 'Grenadier', 25, 25, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(121, 4, 1, 4, 9, 7, 'German Grenadier', 'Grenadier', 25, 25, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(122, 1, 1, 4, 9, 8, 'British Commander', 'Commander', 0, 0, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(123, 4, 1, 4, 9, 8, 'German Commander', 'Commander', 0, 0, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(124, 3, 1, 4, 9, 8, 'French Commander', 'Commander', 0, 0, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(125, 1, 1, 4, 9, 9, 'British ATR', 'ATR', 25, 25, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(126, 3, 1, 4, 9, 9, 'French ATR', 'ATR', 25, 25, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(127, 4, 1, 4, 9, 9, 'German ATR', 'ATR', 25, 25, 'False', '2004-08-23 15:01:05', '2014-05-19 17:35:26'),
								(128, 1, 1, 2, 7, 5, 'QF 6pdr', 'QF 6pdr', 0, 0, 'True', '2004-10-07 17:45:39', '2014-05-19 17:35:26'),
								(129, 3, 1, 2, 7, 5, '57mm M1', '57mm M1', 0, 0, 'True', '2004-10-07 17:46:21', '2014-05-19 17:35:26'),
								(130, 4, 1, 2, 7, 5, 'Pak38', 'Pak38', 0, 0, 'True', '2004-10-07 17:46:49', '2014-05-19 17:35:26'),
								(131, 1, 1, 2, 4, 8, 'Crusader MkIII', 'Crusader III', 0, 0, 'True', '2004-12-06 15:13:24', '2014-05-19 17:35:26'),
								(132, 4, 1, 2, 4, 9, 'StuG IIIG', 'StuG IIIG', 0, 0, 'True', '2004-12-06 15:13:24', '2014-05-19 17:35:26'),
								(133, 4, 1, 2, 4, 8, 'PzKpfw IVg', 'Pz IVg', 0, 0, 'True', '2004-12-06 15:13:24', '2014-05-19 17:35:26'),
								(134, 3, 1, 2, 4, 7, 'Sherman M4a2', 'Sherman', 0, 0, 'True', '2004-12-06 15:13:24', '2014-05-19 17:35:26'),
								(135, 3, 2, 1, 2, 2, 'Spitfire Mk I', 'Spit I', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(136, 3, 2, 1, 2, 3, 'Hurricane Mk I', 'Hurri I', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(137, 1, 1, 4, 9, 10, 'British Paratrooper', 'Paratrooper', 200, 200, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(138, 3, 2, 1, 2, 7, 'P38f', 'P38f', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(139, 4, 2, 1, 1, 5, 'Ju52', 'Ju52', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(140, 3, 2, 1, 1, 3, 'French Ju52', 'Ju52', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(141, 4, 1, 4, 9, 10, 'German Paratrooper', 'Paratrooper', 200, 200, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(142, 3, 1, 4, 9, 10, 'French Paratrooper', 'Paratrooper', 200, 200, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(143, 1, 2, 1, 1, 4, 'British Ju52', 'Ju52', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(144, 4, 2, 1, 2, 4, 'Fw 190A4', 'Fw 190A4', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(145, 1, 2, 1, 2, 6, 'Spitfire IXc', 'Spitfire IXc', 0, 0, 'True', '2004-12-25 12:16:05', '2014-05-19 17:35:26'),
								(146, 1, 1, 2, 4, 9, 'Churchill VII', 'Churchill 7', 0, 0, 'True', '2005-11-09 14:09:03', '2014-05-19 17:35:26'),
								(147, 3, 1, 2, 4, 8, 'M10-TD', 'M10-TD', 0, 0, 'True', '2005-11-09 14:10:10', '2014-05-19 17:35:26'),
								(148, 4, 1, 2, 4, 10, 'PzKpfw VI E Tank', 'Tiger', 0, 0, 'True', '2005-11-09 14:12:12', '2014-05-19 17:35:26'),
								(149, 1, 1, 2, 4, 10, 'Churchill III', 'Churchill 3', 0, 0, 'True', '2005-12-22 14:45:34', '2014-05-19 17:35:26'),
								(150, 1, 1, 4, 9, 12, 'British Sniper', 'Sniper', 10, 10, 'False', '2005-12-22 14:47:16', '2014-05-19 17:35:26'),
								(151, 3, 1, 4, 9, 12, 'French Sniper', 'Sniper', 10, 10, 'False', '2005-12-22 14:48:05', '2014-05-19 17:35:26'),
								(152, 4, 1, 4, 9, 12, 'German Sniper', 'Sniper', 10, 10, 'False', '2005-12-22 14:48:30', '2014-05-19 17:35:26'),
								(153, 3, 1, 2, 4, 9, 'Sherman M4a3-76', 'Sherman 76', 0, 0, 'True', '2006-10-05 11:57:00', '2014-05-19 17:35:26'),
								(154, 4, 1, 2, 7, 6, 'Pak40', 'Pak40', 0, 0, 'True', '2006-12-22 14:54:00', '2014-05-19 17:35:26'),
								(155, 3, 1, 2, 7, 6, '3in M5 ATG', '3in M5', 0, 0, 'True', '2006-12-22 14:59:00', '2014-05-19 17:35:26'),
								(156, 1, 1, 2, 7, 6, 'QF 17pdr', 'QF 17pdr', 0, 0, 'True', '2006-12-22 15:00:00', '2014-05-19 17:35:26'),
								(157, 4, 2, 1, 6, 2, 'Bf110f-B', 'Bf110f-B', 60, 60, 'True', '2009-02-11 12:00:00', '2014-05-19 17:35:26');		
		");
	}
}
