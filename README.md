# OuterOrbit
a myspace clone. based off spacemy.xyz-rebooted by the-real-sumsome.

## dependencies
requires composer... u shoudl know how to use composer

uses steam auth thing..,, >https://github.com/SmItH197/SteamAuthentication

uses php 7.3.... u can use a lower php verison but idk if it will work

uses mysqli/mysql server,...;

## how 2 setup
```
git clone https://github.com/haydenwalker980/outerorbit.git
mv ./outerorbit /your/webserver/dir/
cd /your/webserver/dir/
php composer.phar install (or composer install)

sudo nano static/config.inc.php
get a steam api key from http://steamcommunity.com/dev/apikey
get a recaptcha priv/pub key from https://www.google.com/recaptcha/admin
fill their respective fields in

import the sql file into phpmyadmin/whatever

sudo service apache2 start

u are done
```

## notes
i just spun up this project in my own free time

## thanks to
the-real-sumsome, as well as the creator of the OG og repo
