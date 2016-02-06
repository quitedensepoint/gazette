# World War II Online : Gazette
Manages the World War II Online Gazette application

## Dependencies
The application relies on the following dependencies in order to function correctly.

* [robmorgan/phinx](https://github.com/robmorgan/phinx)
* [monolog/monolog](https://github.com/Seldaek/monolog)

Use *composer install* as described below to install these dependencies.

### Installation

* Install composer from http://getcomposer.org
* From your gazette command line, run *composer install*. This will install the dependencies as listed above. They will not otherwise interfere with the gazette.
* Create a new file called dbConn.php in your application root. Use the example *dbConn.example.php* in the project root as a template.
* Update the database connection strings in the dbConn.php file to match your environment.

### Database modifications
Database migrations are performed with the phinx library (http://phinx.org). As they were introduced during the project,
several raw SQL based migrations had already been performed on the database. These changes have been converted to
Phinx migrations, and these migrations must be added to the Phinx data.

If your current Gazette database already has the **phinxlog table**, you can skip the next step and its sub-steps

* Go into PHPMyAdmin for the database and run the SQL script at *database/phinx_init.sql*
 * This will initialise the phinxlog table and setup the migrations that have already been done in the Gazette.
* If you are installing the gazette from scratch, the file *database/gazette-initial.zip* contains the state of the gazette as 14th December 2015.
 * In this case, you can just call *vendor/bin/phinx migrate* directly from the command to bring the database up to the current state
  * use *vendor/bin/phinx.bat* for Windows installations

The documentation for phinx migrations can be found at the [Phinx Website](http://docs.phinx.org/en/latest/migrations.html)

### Logging
The application uses the monolog library for debug and runtime logs. Refer to the monolog documentation and the usage in the *commands/campaign-check.php* script for examples on how to output to logs.

Currently the logger is not using any handlers, so it just outputs everything to the console.

## Scripts
### Campaign Checker
The campaign checked should be run via CRON job every *5* minutes. You can set this to a shorter time if necessary, but it should not be more than 5 minutes.

> *php commands/campaign-check.php*

This will check the *wwiionline* and *community* databases and determine the current state of the game. It will update the gazette based on these values.

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