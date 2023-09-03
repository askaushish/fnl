<?php

namespace App\Http\Controllers;

use App\City;
use App\Services\OpenWeatherService;
use Exception;
use Illuminate\Http\Request;

class CityWeatherController extends Controller
{

    private $openWeatherService;
    /**
    * Create a new controller ins
    *
    * @return void
    */
    public function __construct(OpenWeatherService $openWeatherService)
    {
        $this->middleware('guest');
        $this->openWeatherService = $openWeatherService;
    }

    /**
     * Function to get weather data for a single city(if provided city in cityName param) or all cities.
     * If city is found in DB, it will return weather forcast data for the city, otherwise it will add that city to database and return weather forcast.
     * If no city is provided and there is no city in database, it will throw error.
     */
    public function getWeatherData(Request $request) {
        $cityName = $request->get('cityName');
        
        try{
            if(!empty($cityName)) {
                // Check city in database first
                $cityData = City::where('name', $cityName)->first();
                if(empty($cityData)) {
                    $geoData = $this->openWeatherService->getGeoDataForCity($cityName);
                    // Add city data to database
                    $cityData = $this->addCity($geoData);
                }
                return $this->openWeatherService->getWeatherForecastForByLatLong($cityData->lat, $cityData->lang);
            } else {
                // Is it wise to get weather data for all cities, as it will hamper response time of API??
                // Anyways I am writing here to get weather forecast data for all cities in database 
                $cities = City::all();
                $cityWeatherData = [];
                foreach($cities as $city) {
                    $cityWeatherData[$city->name] = $this->openWeatherService->getWeatherForecastForByLatLong($city->lat, $city->lang);
                } 
                return $cityWeatherData;               
            }
        } catch (Exception $e) {
            // Log exception
            return ['code' => $e->getCode(), 'message' => $e->getMessage(). " at line " . $e->getLine()];
        }

    }

    /**
     * Function to add new city with geo data
     */
    public function addCity($cityGeoData) {
        
        $city = new City;
        $city->name = $cityGeoData['name'];
        $city->lat = $cityGeoData['lat'];
        $city->lang = $cityGeoData['lon'];
        $city->state = $cityGeoData['state'];
        $city->country = $cityGeoData['country'];
        $city->save();
        return $city;
    }
}
