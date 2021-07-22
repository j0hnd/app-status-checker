## Requirements

- PHP 7.4
- MySQL 8 
- NodeJS 14.7.1
- NPM 6.14.13

## Installation

- Clone this repository
- After cloning, go to the cloned project and run **composer install**
- Login to your MySQL server and create a empty database
- copy .env.example to .env

In your .env file, supply the necessary details.

###Run these artisan commands:
- php artisan migrate
- php artisan ui bootstrap
- php artisan db:seed --class=DefaultUserSeeder

### Run these NPM commands:
- npm install
- npm run <dev|prod>

### Pusher ###
make sure you have a Pusher account and supply your credentials on .env file

### Update .env ###
1. change BROADCAST_DRIVER value to **pusher**

### Scheduling ###
add **schedule:run** on cronjob and set the execution time every minute.
