# Silo
Simple server resource panel in PHP. Monitor load, disk and memory usage with custom alert limits set within Silo. Receive email alerts when you go over these set limits.

## Features
* Clean and responsive
* Show disk, memory and load usage
* Email alerts
* Configure all settings on the web interface
* Create daily logfiles
* No database needed

## Requirements
* PHP 5.5+
* VPS/Dedicated server recommended. Not made for shared hosting environments.
* Git (recommended)

## Installation
1. Browse to your desired install directory
1. Execute git clone https://github.com/ialexpw/Silo.git
1. Ensure the /resources/data directory is writable
1. Set up a cronjob to /resources/cron.php every 5 minutes
1. Log in to the web interface with the default password "password"
1. Good to go!

## Updating Silo
Updating can be done from the command line with a git pull.

## Demo
You can see the frontend by going to https://perfnet.org/Silo/ - to see the configuration screens, refer to the screenshots.

## Screenshots
Coming soon..

## Licence
MIT License - Please view the LICENSE file.
