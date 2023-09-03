Follow these steps to run the application 

1. Create .env file and change database configuration accordingly.
2. Run : composer install
3. Run : php artisan migrate
4. Hit following URL with postman : 
    localhost:8000/api/weatherData?cityName=London

    Here are two scenarios : If you pass cityName parameter : 
    - If city exists in database, it will return weather forcast data for the city.
    - If city does not exist, it will get geolocation data from openweather and if we get geo data from there, we will add new city in database and return weather data for newly added city

    If you don't pass cityName parameter : 
    - If city data exist in database, then weather forecast data will be returned for each city, with city name as the key.
    - If there is no data in city database, then application will throw exception and will ask user for providing a city.

