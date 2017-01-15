# BackMeUp

## Setup Instructions
* Clone this git repo
* Do `composer install` to install server side dependencies
* Do `npm install` to install node dependencies. Make sure you have node js on your machine
* Add Mysql config in .env file
* Run `php artisan migrate` to setup your database from the schema
* Run app using `php artisan serve`. Browse it on localhost:8000

## Uses
* Laravel Framework
* Eloquent ORM
* Blade Template Engine (PHP)
* PHPSECLIB for SSH and SFTP http://phpseclib.sourceforge.net/
* LESS, Gulp

## Structure
* Uses Repositories for logic and data fetching
* Uses Controllers for handling views
* Uses Models for data
* Site passwords are stored base64 encoded to prevent direct visibility
* Backups are stored in /backups folder of this app

## What this app doesn't Handle/include
* Edge error cases if remote hang up, memory issues, or compression problems on remote end
* Validation for each field. Uses basic HTML5 validation for test purpose
* Usage of any Javascript framework. As 99% of the app purpose is server based. Yet, the javascript is very modular and normalized

## What can be improved
* Displaying fancy alert messages or information
* Background processing. Currently the backups are handled from PHP which is a blocking language. Moving to bash scripting or node js or as a different service on another server would help on this part.
* Uploading backups on a different server / directory instead of this app itself
