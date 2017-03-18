<?php
    // For Allied side specific information

    // Enable output buffering - this can increase performance and allows us to handle errors better
    ob_start();

    //include 'testkills.php';
    require(__DIR__ . '/../DBconn.php');
    require(__DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'alliedstats.php');

    $stat1 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats1.php');
    $stat2 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats2.php');
    $stat3 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats3.php');
    $stat4 = file_get_contents(__DIR__ .'/../cache/playnow_allied_stats4.php');
    $funit = file_get_contents(__DIR__ .'/../cache/playnow_allied_french_spawn.php');
    $bunit = file_get_contents(__DIR__ .'/../cache/playnow_allied_british_spawn.php');
    $promo = file_get_contents(__DIR__ .'/../cache/playnow_allied_promotion.php');

    function toTd($item) {
        $contents = $item;
        if(is_numeric($item)) {
            $contents = round($item, 0);
        };
        return "<td>" . $contents . "</td>";
    }

    //Pull DB rows for stat tables.

    $daaRow = $daa->fetch_assoc();
    $datgRow = $datg->fetch_assoc();
    $datrRow = $datr->fetch_assoc();
    $dengRow = $deng->fetch_assoc();
    $dgrenRow = $dgren->fetch_assoc();
    $dlmgRow = $dlmg->fetch_assoc();
    $dmortRow = $dmort->fetch_assoc();
    $drifleRow = $drifle->fetch_assoc();
    $dsmgRow = $dsmg->fetch_assoc();
    $dsniperRow = $dsniper->fetch_assoc();
    $dtankRow = $dtank->fetch_assoc();
    $dtruckRow = $dtruck->fetch_assoc();
    $dfightRow = $dfight->fetch_assoc();
    $dbombRow = $dbomb->fetch_assoc();
    $dddRow = $ddd->fetch_assoc();
    $dpbRow = $dpb->fetch_assoc();
    $dttRow = $dtt->fetch_assoc();

    $taaRow = $taa->fetch_assoc();
    $tatgRow = $tatg->fetch_assoc();
    $tatrRow = $tatr->fetch_assoc();
    $tengRow = $teng->fetch_assoc();
    $tgrenRow = $tgren->fetch_assoc();
    $tlmgRow = $tlmg->fetch_assoc();
    $tmortRow = $tmort->fetch_assoc();
    $trifleRow = $trifle->fetch_assoc();
    $tsmgRow = $tsmg->fetch_assoc();
    $tsniperRow = $tsniper->fetch_assoc();
    $ttankRow = $ttank->fetch_assoc();
    $ttruckRow = $ttruck->fetch_assoc();
    $tfightRow = $tfight->fetch_assoc();
    $tbombRow = $tbomb->fetch_assoc();
    $tddRow = $tdd->fetch_assoc();
    $tpbRow = $tpb->fetch_assoc();
    $tttRow = $ttt->fetch_assoc();

    $dkillsRow = $dkills->fetch_assoc();
    $dcapsRow = $dcaps->fetch_assoc();
    $dkdRow = $dkd->fetch_assoc();
    $dtomRow = $dtom->fetch_assoc();
    $dkstreakRow = $dkstreak->fetch_assoc();
    $dcapstreakRow = $dcapstreak->fetch_assoc();
    $dkrowRow = $dkrow->fetch_assoc();
    $dcrowRow = $dcrow->fetch_assoc();

    $tkillsRow = $tkills->fetch_assoc();
    $tcapsRow = $tcaps->fetch_assoc();
    $tkdRow = $tkd->fetch_assoc();
    $ttomRow = $ttom->fetch_assoc();
    $tkstreakRow = $tkstreak->fetch_assoc();
    $tcapstreakRow = $tcapstreak->fetch_assoc();
    $tkrowRow = $tkrow->fetch_assoc();
    $tcrowRow = $tcrow->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>World@War Allied Gazette</title>
	<link rel="shortcut icon" href="assets/img/favicon.ico" />
    <script type='text/javascript' src='bundle.js'></script>
	<link href='assets/css/styles.css' rel='stylesheet'></link>
</head>

<body>
    <div class="gazette container" style="background-image: url('assets/img/paper_tile.png')">
    	<div class="row header align-middle">
    		<div class="col-xs-8 headline textFit">
                <h1>Allied Forces Edition</h1>
    		</div>
    		<div class="col-xs-4 text-center">
                <img src='assets/img/logo.png' class="logo">
            </div>
    	</div>
    	<div class="row campaign-data">
    		<div class="col-xs-4 text-center">
                <a href="http://www.battlegroundeurope.com/index.php/component/content/category/17-development-notes">What's Coming Next</a>
    		</div>
    		<div class="col-xs-4 text-center">
                <a href="/">Front Page</a>
    		</div>
    		<div class="col-xs-4 text-center">
                <a href='axis.php'>Axis Section</a>
    		</div>
    	</div>
    		<div class="col-xs-4 reports">
                <div class="row">
                        <h2>High Command Seeking Officers!</h2>
                        <p>
                            The Allied Side is calling out to all allied players of yesterday and today.
                            The team is working together and we need YOUR help. Victory can only be earned if all allied players team together and work towards a common goal.
                            The allied side needs players willing to step forth from the ranks to be leaders. <b>YOU</b> can be that leader.
                        </p>
                        <h4>
                            <a href='http://battlegroundeurope.net/ocs' target='_blank'>Learn More</a>
                        </h4>
                </div>
                <div class="row">
                    <h3>Know Your Enemy</h3>
						<!-- Code for image rotation -->
						<?php
                            $iDir='assets/img/allied/units/';
                            $image = glob($iDir.'*.{jpg,png,gif}', GLOB_BRACE);
                            $selImage = $image[array_rand($image)];
                        ?>
                        <img class="poster know-your-enemy" src="<?php echo $selImage; ?>">
						<!-- End rotation Code -->
                </div>
                <div class="row">
                    <p><?php echo $stat3; ?></p>
                </div>
                <div class="row">
                    <p><?php echo $stat4; ?></p>
                </div>
    		</div>
            <div class="col-xs-4 reports">
                <div class="row">
                        <h3>Units In The Field</h3>
                        <table class="report-table alternating">
                            <tr>
                                <th>French</th>
                                <th>British</th>
                            </tr>
                            <tr>
                                <td><?php echo $funit ?></td>
                                <td><?php echo $bunit ?></td>
                            </tr>
                        </table>
                </div>
                <div class="row">
                    <p>
                        <?php echo $stat1; ?></p>
                    </p>
                    <p>
                        <?php echo $stat2; ?>
                    </p>
                </div>
            </div>
    		<div class="col-xs-4 reports">
    			<h3>Allied Leaderboard</h3>
                <table class="report-table alternating">
                    <tr><th>Category</th><th colspan='2'>Last 24 Hours</th><th colspan='2'>Campaign Leaders</th></tr>
                    <tr><th>By Unit - Ground</th><th>Total</th><th>Player</th><th>Total</th><th>Player</th></tr>
                    <tr>
                        <td>Anti-Air Gun</td>
                        <?php
                            echo toTd($daaRow['aaapoints']);
                            echo toTd($daaRow['aaacallsign']);
                            echo toTd($taaRow['aaapoints']);
                            echo toTd($taaRow['aaacallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Anti-Tank Gun</td>
                        <?php
                            echo toTd($datgRow['atgpoints']);
                            echo toTd($datgRow['atgcallsign']);
                            echo toTd($tatgRow['atgpoints']);
                            echo toTd($tatgRow['atgcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Anti-Tank Rifle</td>
                        <?php
                            echo toTd($datrRow['atrpoints']);
                            echo toTd($datrRow['atrcallsign']);
                            echo toTd($tatrRow['atrpoints']);
                            echo toTd($tatrRow['atrcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Engineer</td>
                        <?php
                            echo toTd($dengRow['engpoints']);
                            echo toTd($dengRow['engcallsign']);
                            echo toTd($tengRow['engpoints']);
                            echo toTd($tengRow['engcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Grenadier</td>
                        <?php
                            echo toTd($dgrenRow['grenpoints']);
                            echo toTd($dgrenRow['grencallsign']);
                            echo toTd($tgrenRow['grenpoints']);
                            echo toTd($tgrenRow['grencallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Light Machine Gun</td>
                        <?php
                            echo toTd($dlmgRow['lmgpoints']);
                            echo toTd($dlmgRow['lmgcallsign']);
                            echo toTd($tlmgRow['lmgpoints']);
                            echo toTd($tlmgRow['lmgcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Mortar</td>
                        <?php
                            echo toTd($dmortRow['mortpoints']);
                            echo toTd($dmortRow['mortcallsign']);
                            echo toTd($tmortRow['mortpoints']);
                            echo toTd($tmortRow['mortcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Rifleman</td>
                        <?php
                            echo toTd($drifleRow['rpoints']);
                            echo toTd($drifleRow['rcallsign']);
                            echo toTd($trifleRow['rpoints']);
                            echo toTd($trifleRow['rcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Submachine Gun</td>
                        <?php
                            echo toTd($dsmgRow['smgpoints']);
                            echo toTd($dsmgRow['smgcallsign']);
                            echo toTd($tsmgRow['smgpoints']);
                            echo toTd($tsmgRow['smgcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Sniper</td>
                        <?php
                            echo toTd($dsniperRow['sniperpoints']);
                            echo toTd($dsniperRow['snipercallsign']);
                            echo toTd($tsniperRow['sniperpoints']);
                            echo toTd($tsniperRow['snipercallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Tank</td>
                        <?php
                            echo toTd($dtankRow['tankpoints']);
                            echo toTd($dtankRow['tankcallsign']);
                            echo toTd($ttankRow['tankpoints']);
                            echo toTd($ttankRow['tankcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Truck</td>
                        <?php
                            echo toTd($dtruckRow['truckpoints']);
                            echo toTd($dtruckRow['truckcallsign']);
                            echo toTd($ttruckRow['truckpoints']);
                            echo toTd($ttruckRow['truckcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <th colspan='5'>By Unit - Air</th>
                    </tr>
                    <tr>
                        <td>Fighter</td>
                        <?php
                            echo toTd($dfightRow['fightpoints']);
                            echo toTd($dfightRow['fightcallsign']);
                            echo toTd($tfightRow['fightpoints']);
                            echo toTd($tfightRow['fightcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Bomber</td>
                        <?php
                            echo toTd($dbombRow['bombpoints']);
                            echo toTd($dbombRow['bombcallsign']);
                            echo toTd($tbombRow['bombpoints']);
                            echo toTd($tbombRow['bombcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <th colspan='5'>By Unit - Sea</th>
                    </tr>
                    <tr>
                        <td>Destroyer</td>
                        <?php
                            echo toTd($dddRow['ddpoints']);
                            echo toTd($dddRow['ddcallsign']);
                            echo toTd($tddRow['ddpoints']);
                            echo toTd($tddRow['ddcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Patrol Boat</td>
                        <?php
                            echo toTd($dpbRow['pbpoints']);
                            echo toTd($dpbRow['pbcallsign']);
                            echo toTd($tpbRow['pbpoints']);
                            echo toTd($tpbRow['pbcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Freighter</td>
                        <?php
                            echo toTd($dttRow['ttpoints']);
                            echo toTd($dttRow['ttcallsign']);
                            echo toTd($tttRow['ttpoints']);
                            echo toTd($tttRow['ttcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <th colspan='5'>By Action</th>
                    </tr>
                    <tr>
                        <td>Most Kills</td>
                        <?php
                            echo toTd($dkillsRow['kills']);
                            echo toTd($dkillsRow['killscallsign']);
                            echo toTd($tkillsRow['kills']);
                            echo toTd($tkillsRow['killscallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Most Caps</td>
                        <?php
                            echo toTd($dcapsRow['caps']);
                            echo toTd($dcapsRow['capscallsign']);
                            echo toTd($tcapsRow['caps']);
                            echo toTd($tcapsRow['capscallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Best K/D</td>
                        <?php
                            echo toTd($dkdRow['kd']);
                            echo toTd($dkdRow['kdcallsign']);
                            echo toTd($tkdRow['kd']);
                            echo toTd($tkdRow['kdcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>Top TOM</td>
                        <?php
                            echo toTd($dtomRow['tom']);
                            echo toTd($dtomRow['tomcallsign']);
                            echo toTd($ttomRow['tom']);
                            echo toTd($ttomRow['tomcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <th colspan='5'>Single Sortie</th>
                    </tr>
                    <tr>
                        <td>With A Kill</td>
                        <?php
                            echo toTd($dkstreakRow['sortmkill']);
                            echo toTd($dkstreakRow['sortmkillcallsign']);
                            echo toTd($tkstreakRow['sortmkill']);
                            echo toTd($tkstreakRow['sortmkillcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>With A Capture</td>
                        <?php
                            echo toTd($dcapstreakRow['capstreak']);
                            echo toTd($dcapstreakRow['sortcapscallsign']);
                            echo toTd($tcapstreakRow['capstreak']);
                            echo toTd($tcapstreakRow['sortcapscallsign']);
                        ?>
                    </tr>
                    <tr>
                        <th colspan='5'>Consecutive Sorties</th>
                    </tr>
                    <tr>
                        <td>With A Kill</td>
                        <?php
                            echo toTd($dkrowRow['krowpoints']);
                            echo toTd($dkrowRow['krowcallsign']);
                            echo toTd($tkrowRow['krowpoints']);
                            echo toTd($tkrowRow['krowcallsign']);
                        ?>
                    </tr>
                    <tr>
                        <td>With A Capture</td>
                        <?php
                            echo toTd($dcrowRow['crowpoints']);
                            echo toTd($dcrowRow['crowcallsign']);
                            echo toTd($tcrowRow['crowpoints']);
                            echo toTd($tcrowRow['crowcallsign']);
                        ?>
                    </tr>
                </table>
                <p><?php echo $promo; ?></p>
    		</div>
    	</div>
    </div>
    </body>
</html>
<?php
// This MUST be present as the last line of this file to ensure buffered content is sent to the browser
ob_end_flush();
// Don't put a closing PHP tag here. It isn't necessary and can introduce problems
