# Connect
Connect is a social network application written in Laravel 12.x (latest version).
Live at [https://connectshare.xyz](https://connectshare.xyz)

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
- minimum php version 8.2
- composer install
- copy .env.example to .env file
- php artisan key:generate to regenerate secure key
- create new mysql database and edit .env file for DB settings
- php artisan migrate --seed (--seed is required for creating the guest account to login as guest)
- storage and bootstrap/cache directories should be writable
- php artisan storage:link
- composer run dev
- finally start the laravel reverb server
```
php artisan reverb:start
```
