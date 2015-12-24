# World War II Online : Gazette

## Database modifications
Database modification files are contained in the **database** directory. In order
to correctly perform the database changes, they must be performed in the ascending
order of the date in the file name.

We must also track which scripts have been executed on the server in order to
maintain the server integrity.

## Scripts
### Campaign Start Script
To start a new campaign, call the following script from the command line of the project root

> *php commands/campaign-start.php --campaignid={id} --start={date}*

Where
* {id} is the campaign id (campaign number)
* {start} is the date and time of the start of the campaign, in the format "yyyy-mm-dd HH:mm:ss". You should put double quotes around the date.

### Campaign Stop Script
To end a campaign, call the following script from the command line of the project root

> *php commands/campaign-stop.php --stop={date}*

Where
* {stop} is the date and time of the stop of the campaign, in the format "yyyy-mm-dd HH:mm:ss". You should put double quotes around the date.

### Casualties calculation script
Run a cron job at the start of every hour to call the following script. This will populate the casualties data for the Gazette top page

> *php commands/casualties.php*

To get a set of the casualties to the command line without writing to the database, use:

> *php commands/casualties.php --report-only*

### Story Generation
To regenerate all stories and reset their lifetimes

> *php commands/stories.php --generate=all*

To regenerate all expired stories and reset their lifetimes

> *php commands/stories.php --generate=expired*

To regenerate a story for single slot on a page

> *php commands/stories.php --generate={story_key}

Where *story_key* is the value in the story_key column in the stories table
