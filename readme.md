## World War II Online - Gazette

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
** I recommend http://www.heidisql.com
* Use git to pull down your branch to the local machine
* In the command line, go to the src directory and type:
 * *composer install*
* A vendor folder will be created and the core libraries will be downloaded and installed on your system. 
 * This will take a few minutes, but you only have to do it once.

## Local Environment
Look for a file called .env.example in the source. This contains an example set of variables related to
the database, email and other connections. The installation scripts above should 
have created a file called ".env" for you, but if not, create it now and copy
the contents of .env.example to it (same directory). 

If it was created by the installer, be sure to prefix all the DB_* variables with 
"COMMUNITY_" like in the example. This gives us the flexibility to add new 
database connections later if we have to.

## Local Web Server setup
### DNS
Add an entry to your local hosts file so that you have a domain to reference.
* Windows users edit the file in %WINDOWS%\system32\drivers\etc\hosts (you'll need an admin level account)
* Mac users, probably in /etc/hosts

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

The **DocumentRoot** should refer to the *web* directory of wherever you checked out
the codebase to. This will be the root of the publicly acccessible site.
In the directory subsection, set it to the **src** directory of where you checked out
the codebase to. This allows us to have be able to reference code that is outside the
publicly accessible directory structure (vital for security).
Log files can go wherever you want to them go, but I recommend somewhere outside the application directories.
**ServerName** should be whatever you used in the hosts file.

Save the file, restart Apache and your app should be good to go at http://gazette.localhost