### Short Url algorithm explanation
The algorithm works by taking the input URL and appending a random number to it
then generation and MD5 hash and then taking the first 7 character of that hash after that
the system check if this hash is already stored un the database if that is the case then generates another one untils it finds an unused hash. 
Thanks to the random number that is appended to URL to generate the hash calling the method twice with the same 
URL won't generate the same hash.

## Run both projects with docker (Recommended)

### `docker pull mysql:5.7.25`
This will pull an image of Mysql

### `docker network create mynetwork`
This will create a network for the php and mysql container to work together

### `docker run -p 3306:3306 --name=short_url_db -e MYSQL_ROOT_PASSWORD=short_url -d --network mynetwork mysql`
This will run the mysql image. the -p 3306:3306 is optional, this argument will foraware all mysql connections to the container
This will allow you to easily connect and create the required database which you must do to run the App.
The database name is "short_url". This is required or the app won't work. Make sure you don't have mysql running in the host machine.


### `sudo docker build -t php-docker .`
In this repo there is a Dockerfile, download the file to a custom location and run this command.
This will create a docker image with the backend and frontend projects ready to run.

### `sudo docker run -p 80:80 --network mynetwork php-docker`
Run this command only when all the above steps have been completed without error. Make sure you don't have apache running on port 80 or any other service.

### [init database](http://localhost/migrate)
Click in that url to run migrations, you will see a black page with exit code of migration command. Make sure the database exists before clicking. This is required to create the App tables.
You can also connect to the container and run `php artisan migrate` in /var/www/html directory
When all this is done you can access [localhsot](http://localhost) and the home page should open

## Run Backend Project without docker

First you should check [laravel requirements](https://laravel.com/docs/5.7#server-requirements), Also this project requires php7.2

Then In the root directory of the project run:

### `composer install`
To install all dependencies

### modify .env.example
Rename .env.example to .env and modify the database parameters according to your needs. the defaults are:

DB_HOST=short_url_db <br>
DB_PORT=3306 <br>
DB_DATABASE=short_url <br>
DB_USERNAME=root <br>
DB_PASSWORD=short_url <br>

### `php artisan key:generate`
Will generate a secret key and store it in the .env file. this is required by laravel

### `php artisan migrate`
One you have the database configure run this command to create the necessary tables

### `php artisan serve`
This is the easiest way to run a laravel project for development. This will run php internal web server listening on port 8000

### Configure the front-end
Follow [this](https://github.com/idekel/url-shorterner-frontend) steps to run the frontend app

## Run unit tests
### `phpunit`
Just run this command on the root of the project. the php-sqlite extension must be install

### Top 100 most visited URLs
Accessing localhost:[8000]/api/top/100/most/visited/urls returns the 100 most visited URLS
also the is an option in the from-end app to see this as per requirements 
