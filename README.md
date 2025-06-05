# StickiesBio



## Requirements

- PHP 7.4 | 8.0
- NodeJs 18.0
- MySql *

## Package Installation

To install laravel package, please run composer install by:
```
composer install
```
To install nodejs package, please run npm install by:
```
npm install
```

## Environtment Preparation

Setup env for database connection
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stickie-link
DB_USERNAME=root
DB_PASSWORD=
```
Setup env for pusher notification

```
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=ap1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

Setup env for SMPT
```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=''
MAIL_FROM_NAME="${APP_NAME}"
```

## Database Migration

After finish installing packages and setup environment, please do migration by
```
php artisan migrate
```
and to get initial data, please run seed by
```
php artisan db:seed
```

## Development Step

To run in your local please do
```
php artisan serve
```
and to run the frontend please do
```
npm run hot
```
and to run the realtime notification need to run laravel queue by
```
php artisan queue:work
```

## Production Step

For production only need to build the frontend by
```
npm run prod
```
and to optimize the laravel you can do
```
php artisan optimize
php artisan config:cache
```

