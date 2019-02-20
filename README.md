### Short Url algorithm explanation
The algorithm works by taking the input URL and appending a random number to it
then generation and MD5 hash and then taking the first 7 character of that hash after that
the system check if this hash is already stored un the database if that is the case then generates another one untils it finds an unused hash. 
Thanks to the random number that is appended to URL to generate the hash calling the method twice with the same 
URL won't generate the same hash.

## Run both projects with docker

### `docker-compose up -d`
Move to docker directory and run this command. You can get information about the containers by going into docker directory and examine the docker-compose.yml file


### [init database](http://localhost/migrate)
Click in that url to run migrations, you will see a black page with exit code of migration command. Make sure the database exists before clicking. This is required to create the App tables.
You can also connect to the container and run `php artisan migrate` in /var/www/html directory
When all this is done you can access [localhsot](http://localhost) and the home page should open

Then In the root directory of the project run:
