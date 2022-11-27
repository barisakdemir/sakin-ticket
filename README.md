# sakin-ticket

Sakin Ticket build on Laravel Framework. <br />
Laravel version: 9.3 <br />
Php version: 8 <br />
PostgreSql version: 12 <br />

## Installation

Use the git and composer to install

```bash
git clone https://github.com/barisakdemir/sakin-ticket
cd sakin-ticket/
cp .env.example .env
```

Edit .env file for your Sakin Ticket App information then

Edit database/seeders/UserSeeder.php file for Sakin Ticket App users credentials.

```bash
composer install
php artisan key:generate
php artisan config:cache
php artisan migrate:refresh --seed
php artisan serve
```

Crontab jobs

```
* * * * * php /srv/sakin-ticket/artisan queue:work --stop-when-empty
```

## Tests on postman import json

[Postman Json File](https://github.com/barisakdemir/sakin-ticket/blob/main/sakin-ticket.postman_collection.json)
