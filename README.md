# Note Taking Project



## First Time Setup Code

```bash
composer install

php artisan migrate:fresh

# provide complete seed for testing up and runnning , user role
php artisan db:seed


php artisan serve

# open localhost:8000/admin 
# login using 
# email : admin@mail.com
# password : password

```


## API Documentation

provide postman collection links
Registration	
POST	    
http://127.0.0.1:8000/api/v1/register

Login	        
POST
http://127.0.0.1:8000/api/v1/register

Lists
GET	
http://127.0.0.1:8000/api/v1/notes

Detail	
GET	       
http://127.0.0.1:8000/api/v1/notes/ {id}

Create
POST	    
http://127.0.0.1:8000/api/v1/notes

Update	
PUT	        
http://127.0.0.1:8000/api/v1/notes/ {id}

Delete
DELETE	    
http://127.0.0.1:8000/api/v1/notes/ {id}


## Bash Logs

all the bash we type

```bash
php artisan make:seeder RoleSeeder
php artisan make:seeder UserSeeder
```


