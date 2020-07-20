# NASA API

1. Create .env file from .env.sample and fill variables
1. docker-compose up -d
2. docker exec -it nasa_php_1 bash
   - composer install
   - php bin/console doctrine:migrations:migrate
3. Enjoy

Endpoints:  
/neo/hazardous/ - Will return potentially hazardous asteroids  
Example: http://localhost:12002/neo/hazardous/ | http://localhost:12002/neo/hazardous/20/0

/neo/fastest?hazardous=(true|false) - Will return the model of the fastest asteroid  
Example: http://localhost:12002/neo/fastest | http://localhost:12002/neo/fastest?hazardous=true  

/neo/best-month?hazardous=(true|false) - Will return a month with most asteroids  
Example: http://localhost:12002/neo/best-month | http://localhost:12002/neo/best-month?hazardous=true  
