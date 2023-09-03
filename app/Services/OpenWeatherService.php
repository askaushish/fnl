<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenWeatherService
{
    protected $client;

    protected $appKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->appKey = config('custom.open_weather_api_key');
    }

    /**
     * Function to make an http GET call
     */
    public function makeAPIRequest($url)
    {
        $response = $this->client->get($url);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get GEO location data for provided city name
     */
    public function getGeoDataForCity($cityName) {
        $geoUrl = 'http://api.openweathermap.org/geo/1.0/direct?q='.$cityName.'&limit=1&appid='.$this->appKey;
        
        $geoData = $this->makeAPIRequest($geoUrl);
        if(empty($geoData)) {
            throw new \Exception("City data not found");
        }
        return $geoData[0];
    }

    /**
     * Get weather forecast data by lat and lang
     */
    public function getWeatherForecastForByLatLong($lat, $lang) {
        $geoUrl = 'http://api.openweathermap.org/data/2.5/forecast?lat='.$lat.'&lon='.$lang.'&appid='.$this->appKey;
        $weatherData = $this->makeAPIRequest($geoUrl);

        if(empty($weatherData)) {
            throw new \Exception("Weather data not available for city");
        }
        return ['cnt' => $weatherData['cnt'], 'data' => $weatherData['list']];
    }
}