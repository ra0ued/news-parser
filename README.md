BBC Twitter Parser
==================

A Symfony project created on May 25, 2017, 2:47 pm.

Prepare application:

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install
chmod +x update.sh
```

Do not forget fill parameters properly, especially twitter API credentials, also set proper rights/mode to app/logs and app/cache directories.

Prepare database:

```
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
```

Configure news feed update by cron:
```
SHELL=/bin/bash
MAILTO=ra0ued@zabtech.ru
* * * * * /var/www/bbc-news/update.sh
```