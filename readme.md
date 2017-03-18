# World War II Online: Gazette
Manages the World War II Online Gazette application

## Dependencies
The application relies on the following dependencies in order to function correctly.

* [robmorgan/phinx](https://github.com/robmorgan/phinx) - Database Migrations
* [monolog/monolog](https://github.com/Seldaek/monolog) - Logging
* [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) - Remote Connectivity

Use *composer install* as described below to install these dependencies.

### Installation

* Install composer from http://getcomposer.org
* From your gazette command line, run *composer install*. This will install the dependencies as listed above. They will not otherwise interfere with the gazette.
* Create a new file called DBconn.php in your application root. Use the example *DBconn.example.php* in the project root as a template.
* Update the database connection strings in the DBconn.php file to match your environment.
* Add the below VirtualHost to your Apache/Hosts setup

```
<VirtualHost _default_:80>
	DocumentRoot "C:\path\to\your\Gazette\web"
	ServerName gazette.dev

	<Directory "C:\path\to\your\Gazette\web">
		AllowOverride All 
		Require all granted
	</Directory>
</VirtualHost>
```

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

The logger will output information to the logs directory in the application root.


## Scripts
### Campaign Checker
The campaign checked should be run via CRON job every *5* minutes. You can set this to a shorter time if necessary, but it should not be more than 5 minutes.

> *php commands/campaign-check.php*

This will check the *wwiionline* and *community* databases and determine the current state of the game. It will update the gazette based on these values. It will additionally set all countries to inactive when a campaign ends, reactivate the initial countries (see the *is_active_initially* field) and activate additional countries as sorties appear for those countries in the main game DB.

### Casualties Calculation
Run a CRON job at the start of every hour to call the following script. This will populate the casualties data for the Gazette front page.

> *php commands/casualties.php*

To get a set of the casualties to the command line without writing to the database, use:

> *php commands/casualties.php --report-only*

### Story Generation
To regenerate all stories and reset their lifetimes

> *php commands/stories.php --generate=all*

To regenerate all expired stories and reset their lifetimes

> *php commands/stories.php --generate=expired*

To regenerate a story for single slot on a page.

> *php commands/stories.php --generate={story_key}*

Where *story_key* is the value in the story_key column in the stories table.

For the purposes of debugging, you can also perform the following options after the generate option

> *php commands/stories.php --generate={story_key} --sourceid={sourcenum} --templateid={templatenum} --typeid={typeid} --force=true*

Where {sourcenum} is the id of the row in the sources table in the gazette. This will be forced to be the text of the story for areas that are being generated.
If you do an "all", all areas will hold this story, which will make it look weird.

{templatenum} will force the system to use the story template located in templates table. Be warned that this will produced stories with weird results if
the template is not normally used against the source (see the template_sources table).

{typeid} will force the system to use the specific story type (e.g. Tactical Stat story) in the area for this story, if it is valid for that area.

The force parameter will try to force a story to be generated, if even the data for the story is out of date. This is mainly to help developers generate output
for player centric stories, if even if the source data is months old. This doesn't work across all story types yet.

The processing logic for stories will be logged to the logs directory. Check the DBconn.example.php for the logging options for stories.

### Stats Update
*This needs to be updated...*

## WebMap
The small WebMap that is on the Gazette is its own small version of the actual WebMap. This means it has its own configuration but it still pulls the needed data from from the WebMap's libraries. 

The WebMap is environment specific, this is so that the proper data is pulled. While testing, if the data was soley pulled from the live server, the times would become offset and then no data would be pulled for the dev server. The environment setting in the DBconn.example.php will allow for configuration.

Setting active to false will disable the webmap entirely - this is useful in development where you are addressing other issues and the webmap is interfering.

> *'webmap' => ['active' => false, 'environment' => 'live']*

## Player Emails
When a story that is system generated references the deeds of a specific player (the story is "player centric"), the system will attempt to generate an email that will go out to the player alerting them of their mention in the gazette. How the email is sent will depend on the settings specific in the options of the DBconn.php. See DBconn.example.php for the settings

The handlers defined in the settings do different things.
- Use *IgnoreHandler* when developing. This ensures that the code runs correctly without modification, but the system will ignore any requests to send an email.
- Use *TestHandler* to test sending out emails so you can see what they look like when developing. This allows you to set an array of recipients to send to, via SMTP.
- Use *RestHandler* for production. This will send requests according to the specification to a remote REST service which will handle placeholder replacements and determining the real recipient to send the email to.

For more information, see the documentation at http://at.playnet.com:8090/display/COMM/Gazette+Player+Emails

## Error Handling
Ensure the config file (DBconn.example.php) for the options variable *error_handling* has the *show_errors* variable set to false. This will ensure the errors aren't shown in the "bombed out" screen. Set it to true for developing.
Errors are stored in "[project root]/logs/errors.log"
