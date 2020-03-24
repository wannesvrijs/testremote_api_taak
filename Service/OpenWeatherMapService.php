<?php

class OpenWeatherMapService
{
    private $base_url_25 = "api.openweathermap.org/data/2.5/";
    private $appid = 'e97bd757a9b4c619b67d39814366db46';
    private $units = "metric";
    private $lang = "nl";

    public function GetWeather( $city )
    {

        $url = $this->base_url_25 . "weather?q=$city" .
                                           "&APPID=$this->appid" .
                                           "&units=$this->units" .
                                            "&lang=$this->lang";

        $curl = curl_init();
        $headers = array();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $json = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($json, true);

        $weatherResult = new WeatherResult();
        $weatherResult->setDesc( ucfirst ($data["weather"][0]["description"] ));
        $weatherResult->setTemp($data["main"]["temp"]);

        return $weatherResult;
    }

}