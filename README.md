# Connect
Connect is a social network application written in Laravel.
Live at [https://connectonline.space](https://connectonline.space)

## Features
- create posts
- react, comment, share and favorite posts
- friendship features
- follow and block features
- real time notifications and events
- browse users, followers and friends
- highly secure authentication, authorizations and validations
- real time messaging

## Installation in Local
- git clone https://github.com/WaiHtikeLin/Connect.git projectname
- cd projectname
- minimum php version 8.1.0
- composer install
- copy .env.example to .env file
- php artisan key:generate to regenerate secure key
- create new database and edit .env file for DB settings
- php artisan migrate
- need to run Reverb's installation command to publish the configuration, add Reverb's required environment variables
```
php artisan reverb:install
```
- storage and bootstrap/cache directories should be writable
- php artisan storage:link
- php artisan serve
- need to run queue worker
```
php artisan queue:work --queue=message,default
```
- finally start the laravel reverb server
```
php artisan reverb:start
```
