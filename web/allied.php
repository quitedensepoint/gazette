<?php
// For Allied side specific information 

require(__DIR__ . '/../DBconn.php');




?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>        
    <title>World@War Allied Gazette</title>
	<link rel='stylesheet' href='assets/css/alliedgazette.css'>
	<link rel="shortcut icon" href="assets/img/favicon.ico" />
</head>

<body>
    <div id='container'>
        <div id='top'>
            <table id='infoBar'>
                <tr>
                    <td style="width: 33%; text-align: left;"><span class='papertimesmedium'>WHATS COMING NEXT</span></td>
                    <td style="width: 33%; text-align: center;"><span class='papertimesmedium'><a href="index.php">READ THE FRONT PAGE</a></span></td>
                    <td style="width: 33%; text-align: right;"><span class='papertimesmedium'>READ THE AXIS SECTION</span></td>
                </tr>
            </table>
        </div>
        <div>
            <span id ="mainHeadline" class="paperheadline"><?php echo "<b>ALLIED FORCES EDITION</b>" ?></SPAN>
            
        </div>
        <div>
            <img src="assets/img/header.gif" id="imagegazette">
        </div>
        <div id="topLeft" >
            <span class="papertimeshuge" ><?php echo"FROM THE<br /> ALLIED COMMAND";?></span>
        </div>
        <div class='clear'></div>
<!-- TOP ROW: Stats Story | Small Story | RDP Block -->
        <div id="topLeftOuterStory">
            <hr style="width:90%; margin-top:0;">
            <span class="papertimesmedium"><?php echo "This would be an absolute fantabulouse spot for an allied story (w no headline)- Maybe recent HC awards (after a recognition system is built), Or stats leaders (after that functionality is built)" ?></span>
        </div>
        <div id="topLeftInnerStory">
            
            <span class="papertimesmedium"><?php echo "<b>Headline</b><br /><hr style='width:90%; margin-top:0;'>This would be an absolute fantabulouse spot for an allied story - Maybe recent HC awards (after a recognition system is built), Or stats leaders (after that functionality is built)" ?></span>
        </div>
        <div id="rdpNote">
            <span class="papertimesmedium"><?php echo "RDP Reports And Such can go into this spot (or can be moved)" ?></span>
        </div>
<!-- Second Row: Small Story | Player Story -->
        <div id="secondLeftOuterStory">
            <span class="papertimesmedium"><?php echo "<b>Headline</b><br /><hr style='width:90%; margin-top:0;'>This would be an absolute fantabulouse spot for an allied story - Maybe recent HC awards (after a recognition system is built), Or stats leaders (after that functionality is built)" ?></span>
        </div>        
        <div id="secondLeftInnerStory">
            <hr style="width:90%; margin-top:0;">
            <span class="papertimesmedium"><?php echo "second -- This would be an absolute fantabulouse spot for an allied story (maybe HC promotions)" ?></span>
        </div>  
<!-- Third Row: Know Enemy | Story/image | Story/Image -->
        <div id="thirdLeftOuterStory">
            <span class="papertimesmedium"><?php echo "Know Your Enemy<br /><br><br>Will be an image per axis unit type with some info.. randomly cycles " ?></span>
        </div>
        <div id="thirdRightOuterStory">
            <span class="papertimesmedium"><?php echo "Something Else<br /><br><br>May be needed to allow for down flexability of some of the RDP stuff (units in field, units introduced, units gone), or used for other things." ?></span>
        </div>


<!-- 4th Row: By Country Factory stats/RDP completion -->
        <div id="bottomLeftOuterStory">
            <span class="papertimesmedium"><?php echo "bottom -- This chunk is for the factory specific RDP information<br>
                                                        Left 1/3 is the BEF factories, mid 1/3 is French, R 1/3 is the 'estimated days until completion' blocks.  " ?></span>
        </div> 





<?php /*  




        



        <div id="bottomMidLeftStory">
            <span class="papertimesmedium"><?php echo "bottom -- This chunk middle bottom Story or something" ?></span>
        </div>


        <div id="bottomRightOuterStory">
            <span class="papertimesmedium"><?php echo "Know Your<br /> Enemy" ?></span>
        </div>
*/ ?>      
































    </div> <?php /* end of background container */ ?>
</body>
</html>
