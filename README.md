bbc_news
========

A Symfony project created on May 25, 2017, 2:47 pm.

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