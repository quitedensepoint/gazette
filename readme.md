# World War II Online - Gazette

## Installation for Production

*to be documented*

## Installation for Local Development

To install this application on a local development machine

* Install a development environment containing PHP 5.6, MySQL, and Apache
 * https://www.apachefriends.org/index.html contains an installable, self contained Apache/MariaDB/PHP stack
* Install composer
 * http://getcomposer.org
 * This allows you to manage the PHP libraries used by the application.
* Install a SQL management tool
 * I recommend http://www.heidisql.com
* Use git to pull down your branch to the local machine
* In the command line, go to the src directory and type:
 * *composer install*
* A vendor folder will be created and the core libraries will be downloaded and installed on your system. 
 * This will take a few minutes, but you only have to do it once.

## Local Environment
Look for a file called .env.example in the source. This contains an example set of variables related to the database, email and other connections. The installation scripts above should have created a file called ".env" for you, but if not, create it now and copy the contents of .env.example to it (same directory). 

If it was created by the installer, be sure to prefix all the DB_* variables with "GAZETTE_" like in the example. This gives us the flexibility to add new database connections later if we have to.

## Local Web Server setup
### DNS
Add an entry to your local hosts file so that you have a domain to reference.

* Windows users edit the file in %WINDOWS%\system32\drivers\etc\hosts (you'll need an admin level account)
 * In your start menu, type **notepad** and when it appears, *right click* and select *Run as Adminstrator**
 * In notepad, do **File > Open** and navigate to the folder above. Make sure the file selector is set as *all files*
 * Open and edit the hosts file and save the fil.
* Mac users, probably in /etc/hosts
 * If you're a Mac developer, you're probably familiar with using sudo to edit the files

On a blank line, add:
* 127.0.0.1     gazette.localhost

> I like to use *gazette.localhost* but you can use whatever name you want locally

Save the hosts file. As this is a system file you'll need admin permission on your local machine.

### Apache Configuration
Add an entry to the file *%apache path%/conf/extra/httpd-vhosts.conf* at the bottom.

``` apache
<VirtualHost _default_:80>
DocumentRoot "D:\Personal Projects\Playnet\gazette\src\web"
ServerName gazette.localhost
ServerAdmin admin@example.com
ErrorLog "D:\Personal Projects\logs\gazette-error.log"
TransferLog "D:\Personal Projects\logs\gazette-access.log"
<Directory "D:\Personal Projects\Playnet\gazette\src">
    AllowOverride All 
    Require all granted
</Directory>
</VirtualHost>
```

The **DocumentRoot** should refer to the *web* directory of wherever you checked out the codebase to. This will be the root of the publicly accessible site. In the directory subsection, set it to the **src** directory of where you checked out
the codebase to. This allows us to have be able to reference code that is outside the publicly accessible directory structure (vital for security). Log files can go wherever you want to them go, but I recommend somewhere outside the application directories.
**ServerName** should be whatever you used in the hosts file.

Save the file, restart Apache and your app should be good to go at http://gazette.localhost

## Laravel Tutorials
I'll be frank, the documentation at the laravel.com website is pretty ordinary. It tells you how to do things, without telling why you are doing it. Enter http://laracasts.com - this will give beginners plenty of guidance.

## Database
The database configuration file at *config/database.php* contains an array of configuration that determine what databases we will be connecting to for the application. By default, the application will  use the default connection, which is the **gazette** connection. The array also contains other configurations for connecting to the **wwii** and **wwiionline** databases, which we will need to do.

Authentication to the various databases is manageable by declaring an **.env** files your application root. If you review the source control, you will see an example file called **env.example**. You can copy the settings in here to a file called **.env** which should sit at the same level as the example.

In this file, you define the settings for your different environments. *This file is unique to each environment and contains the credentials for that environment. It should never be checked in with your code*.

The Laravel framework will read this file for connection information to your databases. All the connections are setup as needed so you'll never need to do database connections yourself again.

### Database Migrations
One of the power features in the framework are the database migration. It allows the developer to programmatically make schema changes to the database in a manageable manner, with the added ability to roll those changes back to any point in history.

The files in *src/database/migrations* are executed sequentially, by the date in the file, by the framework. Each change made by the framework is recorded in the **migrations** table, ensuring that changes to the database are tracked, and can be rolled back to previous versions without issues.

> Note that this cannot reverse destructive changes to the schema that results in data loss. Be sure to back up before you run destructive migrations on live systems!

If you look at the very first file in the migrations directory, you will see two functions, **up** and **down**. The **up** function performs the change listed in the closure function. The **down** function reverses that change. You can see in the first file that the first change is to create a new database table called *campaign_casualties*. This function lists all of the database fields that are to be created in this table ( a list of all types can be found at http://laravel.com/docs/5.1/migrations#creating-columns ).

To execute the schema upgrade (i.e create the table), from the command line

> *php artisan migrate*

These executes everything in your **up** function. Once this is complete, you will see your new database table in your database, all brand spanking new.

If you don't like this table, and you want to add an extra field, just rollback the change:

> *php artisan migrate:rollback*

This will perform the changes you created in the **down** function. Generally speaking, you just do your **up** changes in the reverse order. Then add your new field, and rerun the first command.

#### Creating a new migration
Migrations are, as stated before, run sequentially by the date in the filename, and rolled back in the reverse order. Every database change you make should be as small as reasonably possible so that if issues arise, you can roll back change at a time until the issue disappears, and you'll have found your broken change.

Let's say I want to introduce an age field to a users table. For we create the migration in which this will occur:

> *php artisan make:migration add_age_to_user*

#### Modifying an existing table
A migration can also modify an existing table. The syntax is very similar to table creation. Want to add a new field to an existing table? Just add this to your **up** function.

```php
Schema::table('users', function ($table) {
    $table->integer('age')->unsigned();
});
```

Of course, if you think an integer is too large for an age column, just add the following to your **down** function

```php
Schema::table('users', function ($table) {
    $table->dropColumn('age');
});
```

#### Important! One change per migration!
A migration represents the smallest possible change you can make. This doesn't mean that if you need to add three new relevant fields to a table, you should create a migration for each field. If there is a change that can be made with a single SQL statement (like adding three new columns), have it in a single migration

#### Important! Don't change old migrations!
If you find that suddenly that email field you created 20 migrations ago actually needs more characters, don't change the old migration. Just create a new migration to modify the field again and change it to what you need.