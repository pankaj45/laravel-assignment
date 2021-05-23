

## Laravel Api

Please make sure that Docker Desktop is installed in the system. 

TO run the project-:

1. Download the project and cd to project directory
2. Run `./vendor/bin/sail up`
3. After that run `docker-compose exec laravel.test php artisan migrate`
4. run `docker-compose exec laravel.test php artisan passport:install`
 
Now you can access the project OpenAPI specification at http://localhost/api/documentation and run the api's

It contains the api's for 
1. Register User
2. Login User
3. Create Loan Application
4. List Loan Applications

Repay Loan functionality is not implemented currently due to time issues. But I can explain the functionality during further rounds :)

Laravel Passport is used for Authentication. 
