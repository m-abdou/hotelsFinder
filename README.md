hotelsFinder
============

This Repo is Rest Api For Hotels you can search by 
  * price from and to 
  * name
  * city 
  * availability range 
  
and sort by 
   
  * price 
  * name
  
  
## Installation   

clone this application from https://github.com/m-abdou/hotelsFinder.git

cd to hotelsFinder directory 

 - run composer install 
 - run bin/console server:run
 - open browser and put this link http://127.0.0.1:8000/api/hotels
    you will get all register hotels
     
 ** test Cases run those commands 
 
  - ./vendor/bin/phpunit tests/AppBundle/Services/Handlers/HotelHandlerTest.php
  - ./vendor/bin/phpunit tests/AppBundle/Services/Handlers/ValidatorTest.php
  - ./vendor/bin/phpunit tests/AppBundle/Services/ManagerTest.php
  - ./vendor/bin/phpunit tests/AppBundle/Form/HotelTypeTest.php
  - ./vendor/bin/phpunit tests/AppBundle/Controller/APIControllerTest.php

 
 ## Documentation
 
 we provide one end point get request with url /api/hotels
 
 - /api/hotels -> retrieve all register hotels 
 - /api/hotels?name=example get all hotels with name 
 - /api/hotels?sort_by=price  get all hotels sorted by price
 - /api/hotels?price_from=5&price_to=10 get hotels by price range
 - /api/hotels?available_from=15-10-2018&available_to=12-11-2018 get hotels by price availability 

we can combine search criteria with multi factor 

 - /api/hotels?price_from=5&price_to=10&sort_by= name 


## Brif 

This application based on php 7.1 and symfony 3.4 

created by middleware concept

 - manger responsible for execute middlewares 
 - Validator layer one of middlewares responsible for chacking validation and error handling 
 - HotelHandler layer two of middlewares responsible for fetch hotels data and allow search by multi options 
 
 
## examples 

Success : 

request : http://127.0.0.1:8000/api/hotels?name=Media%20One%20Hotel
response : 

 {
    "error": {
    "status": false
    },
    "hotels": [{
         "name": "Media One Hotel",
         "price": 102.2,
         "city": "dubai",
         "availability": [
            {
                "from": "10-10-2020",
                "to": "15-10-2020"
            },
            {
                "from": "25-10-2020",
                "to": "15-11-2020"
            },
            {
                "from": "10-12-2020",
                "to": "15-12-2020"
            }
         ]
    }]
 }

fail: 

request: http://127.0.0.1:8000/api/hotels?price_to=dsfsdf

response:
 {
    "error": {
    "status": true
    },
    "messages": {
        "price_to": ["This value is not valid."]
    }
 } 