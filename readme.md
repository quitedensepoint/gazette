# World War II Online : Gazette

## Database modifications
Database migrations are performed with the phinx library (http://phinx.org). As they were introduced during the project,
several raw SQL based migrations had already been performed on the database. These changes have been converted to
Phinx migrations, and these migrations must be added to the Phinx data.

### Installation

* Install composer from http://getcomposer.org
* From your gazette command line, run *composer install*. This will install the phinx libraries. They will not otherwise interfere with the gazette.
* If your current Gazette database already has the **phinxlog table**, you can skip the next step and its sub-steps
* Go into PHPMyAdmin for the database and run the SQL script at *database/phinx_init.sql*
 * This will initialise the phinxlog table and setup the migrations that have already been done in the Gazette.
* If you are installing the gazette from scratch, the file *database/gazette-initial.zip* contains the state of the gazette as 14th December 2015.
 * In this case, you can just call *vendor/bin/phinx.bat migrate* directly from the command to bring the database up to the current state

### Usage
The documentation for phinx migrations can be found at http://docs.phinx.org/en/latest/migrations.html



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

> *php commands/stories.php --generate={story_key}*

Where *story_key* is the value in the story_key column in the stories table

For the purposes of debugging, you can also perform the following options after the generate option

> *php commands/stories.php --generate={story_key} --sourceid={sourcenum} --templateid={templatenum}*

Where {sourcenum} is the id of the row in the sources table in the gazette. This will be forced to be the text of the story for areas that are being generated.
If you do an "all", all areas will hold this story, which will make it look weord

{templatenum} will force the system to use the story template located in templates table. Be warned that this will produced stories with weird results if
the template is not normally used against the source (see the template_sources table)
