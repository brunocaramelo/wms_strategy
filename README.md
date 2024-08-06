# SIMPLE PRIORITY QUERY APPLICATION


## Technical Specifications

This application has the following specifications: 

| Tool | Version |
| --- | --- |
| Docker | 24.0.7, |
| Docker Compose | 1.29.2 |
| Nginx | 1.19.10 |
| PHP | 8.3.9 |
| Postgre | 16.3 |
| Sqlite (Unit Tests) | 3.16.2 |
| Laravel Framework | 11.14.* |

The application is separated into the following containers

| Service | Image | Motivation
| --- | --- | --- |
| postgres | postgres:latest | Main database |
| php | php-wms | Main Application (Web) |
| web (nginx) | nginx:alpine | Web Server |

## Requirements
    - Docker
    - Docker Daemon (Service)
    - Docker Compose

## Installation procedures
    Procedures for installing the application for local use

1- Download repository 
    - git clone https://github.com/brunocaramelo/wms_strategy.git
       
        we must copy .env.docker-compose to .env with the command below:

        - cp docker/docker-compose-env/application.env.example docker/docker-compose-env/application.env
        - cp docker/docker-compose-env/database.env.example docker/docker-compose-env/database.env

2 - Check that the ports:

    - 443 (nginx) 
    
    - 9000(php-fpm)

    - 5432(postgres) 

     are busy.


3 - Enter the application's home directory and run the following commands:
    
    1 - docker-compose up -d;

    2 - docker exec -t php-wms php /app/artisan migrate;

    3 - docker exec -t php-wms php /app/artisan db:seed;

    4 - docker exec -t php-wms ./vendor/bin/phpunit;

    ### Description of steps (in case of problems)

    1 - for the images to be stored and executed and upload the instances
        
        (NOTE) - due to composer's delay in bringing up the dependencies, there are 3 alternatives,
        
            1 - RUN sudo docker-compose up; without being a daemon the first time, so that you can check the progress of the installation of dependencies.
            
            2 - Wait 20 minutes or so for the command to be executed, to avoid autoloading for example.
            
            3 - If you have any problems with dependencies, run the command below to secure them.
                sudo docker exec -t php-sample composer install;
    
    2 - for the framework to generate and apply the mapping for the database (SQL), which can be Mysql, PostGres, Oracle, SQL Server or SQLITE for example
    
    3 - for the framework to apply changes to the database data, in the case of inserting a first user.
    
    4 - generation of a hash key for use by the system as a validation key.
    
    5 - for the framework to run the test suite.
        - API tests  
        - unit tests
     
### Resolution of possible problems:

#### Problems with dependencies/autoload (Step 1)
    Due to the delay in composer bringing up the dependencies, there are 3 alternatives,
        
            1 - RUN sudo docker-compose up; without being a daemon the first time, so that you can check the progress of the installation of dependencies.
            
            2 - Wait 20 minutes or so for the command to run, in order to avoid autoload errors, for example.
            
            3 - If you have any problems with dependencies, run the command below to secure them.
                sudo docker exec -t php-sample composer install;

#### Problems with Webserver permission to the exposed volume (Step 6)
    - You may also have problems with Webserver permission to the /app volume (or subdirectories)
      Even though it's not indicated, but because it's a local environment, you can force the execution of permissions with:
       - sudo docker-compose exec web chmod 777 -R /app    

## Post Installation

After installation, the access address is:

- https://localhost

- in docs, retrieve the collection (estrategia_WMS_bruno-caramelo.postman_collection.json) from the Postman project called : Estrategia WMS

## Details

    - Laravel 11

    - SOLID

    - Unit Tests

    - Docker and docker-compose
