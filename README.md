# Gracia Media Web Test
## Create a Symfony Project
To create an application in Symfony you need firstly to have composer installed and then with composer you can create a Symfony project easily with the following command:
```
composer create-project symfony/website-skeleton:"5.3.x@dev" my-project-name
```
## Deploy the Application
Once the Application is developed, the way to deploy it locally can be done with the following steps:
- first install dependencies
```
compser install
```

- Next you have to set up the parameters for your local DB client in the .env with your own *dbUser*,*dbPassword*,*dbPort* and *dbName*.
```
DB_URL="mysql://dbUser:dbPassword@localhost:dbPort/dbName?serverVersion=8&charset=utf8mb4"
```
- Then deploy the DB locally:
``` 
php bin/console doctrine:database:create
```
- Then execute the migrations to create the DB tables structure in your local DB:
``` 
php bin/console doctrine:migrations:migrate
```
- Now, to seed our DB with the data of the Star Wars API you can run the customized command:
1) Create the command and in the generated *StarwarsimportCommand.php* introduce your logic:
```
php bin/console make:command
```

2) Execute the command
```
php bin/console starwars:import
```
Now our DB is complete!

## Check the process

You can launch the server to check the endpoint homepage with the data for characters in database like this:
``` 
php -S localhost:8000 -t public/
```
or 
``` 
symfony serve
```
And in your browser go to http://localhost:8000/swapi-front/ or in the URL *symfony serve* gives

