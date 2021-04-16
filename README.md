# Silo
Simple server resource panel in PHP. Monitor load, disk and memory usage with custom alert limits set within Silo. Receive email alerts when you go over these set limits.

## Features
* Clean and responsive
* Show and monitor disk, memory and load usage
* Monitor RAID status (soon)
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
You can see the frontend by going to http://silo.picotory.com - to see the configuration screens, refer to the screenshots.

## Screenshots

### Homepage
![image](https://user-images.githubusercontent.com/7994724/115004480-c01bc980-9e9e-11eb-9b03-3b03061514b4.png)

### Configurator
![image](https://user-images.githubusercontent.com/7994724/115004553-d3c73000-9e9e-11eb-9e59-daaec1f1d874.png)


## Licence
MIT License - Please view the LICENSE file.
