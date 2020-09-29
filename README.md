# spacemy.xyz-rebooted
A fork/faithful recreation of typicalname0/spacemy.xyz

## dependencies
requires composer... u shoudl know how to use composer

uses steam auth thing..,, >https://github.com/SmItH197/SteamAuthentication

uses php 7.3.... u can use a lower php verison but idk if it will work

uses mysqli/mysql server,...;

## how 2 setup
```
git clone https://github.com/the-real-sumsome/spacemy.xyz-rebooted.git
mv /your/git/directory /your/webserver/dir/
cd /your/webserver/dir/
php composer.phar install

sudo nano static/config.inc.php
get a steam api key from http://steamcommunity.com/dev/apikey
get a recaptcha priv/pub key from https://www.google.com/recaptcha/admin

import the sql file into phpmyadmin/whatever

sudo service apache2 start

u are done
```

## notes
this is a project just for experimenting with php. u can contribute if there are some security issues.

## thanks to
everyone who has helped me web dev
