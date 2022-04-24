## About

Smart Home Automation - Backend

### Installation Instructions

1. Run `git clone https://github.com/orvinothkumar/emsol_home_automation_web.git`
2. Create a MySQL database for the project
    - `mysql -u root -p`
    - `create database home_automation;`
    - `\q`
3. From the projects root run `cp .env.example .env`
4. Configure your `.env` file
5. Run `composer update` from the projects root folder
6. From the projects root folder run:

```
php artisan vendor:publish
```

7. From the projects root folder run `sudo chmod -R 755 ../securun`
8. From the projects root folder run `php artisan key:generate`
9. From the projects root folder run `composer dump-autoload`
10. From the projects root folder run `php artisan migrate`
11. From the projects root folder run `php artisan db:seed`
12. Compile the front end assets with [npm steps](#using-npm)

#### Build the Front End Assets with Mix

##### Using NPM:

1. From the projects root folder run `npm install`
2. From the projects root folder run `npm run dev` or `npm run production`

-   You can watch assets with `npm run watch`

#### Build Cache & Storage link

1. From the projects root folder run `php artisan config:cache`
2. From the projects root folder run `php artisan storage:link`

###### And thats it with the caveat of setting up and configuring your development environment.

## Useful Commands During Development

-   php artisan serve --host=172.20.10.9 --port=8081
-   php artisan config:cache
-   php artisan optimize:clear
-   php artisan key:generate
-   php artisan livewire:make test
-   php artisan livewire:delete test
-   php artisan make:model Test -m
-   php artisan make:controller TestController
-   ps -ef | grep php
-   kill -9 17598
-   sudo service apache2 reload
-   sudo systemctl restart apache2
-   SET SQL_MODE='ALLOW_INVALID_DATES'
-   sudo chown -R :www-data storage
-   sudo chown -R :www-data bootstrap/cache/
-   chmod -R 775 storage
-   chmod -R 775 bootstrap/cache/
-   sudo a2ensite \*
-   sudo a2enmod rewrite
-   sudo mysql -u root -p
-   SELECT user, host FROM mysql.user;
-   SET GLOBAL validate_password.policy = 0;
-   CREATE USER 'db_username'@'localhost' IDENTIFIED BY 'db_password';
-   GRANT ALL PRIVILEGES ON _._ TO 'db_name'@'localhost';
-   FLUSH PRIVILEGES;
-   SELECT db, host, user FROM mysql.db WHERE db = 'db_name';
-   GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, REFERENCES, CREATE TEMPORARY TABLES, LOCK TABLES ON db_name.\* TO 'db_name'@'localhost';
