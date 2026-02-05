<?php 


namespace App\Services;

use GuzzleHttp\Client;

class DistanceService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('google_map_api.api_key');
    }

    public function getDistance($postcode1, $postcode2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins={$postcode1}&destinations={$postcode2}&key={$this->apiKey}";

        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);

        if ($data['status'] == 'OK' && $data['rows'][0]['elements'][0]['status'] == 'OK') {
            return $data['rows'][0]['elements'][0]['distance']['value']; // Distance in meters
        }

        return null;
    }
    public function getRegionFromPostcode($postcode)
    {
        
        if (preg_match('/^[A-Za-z]+/', $postcode, $matches)) {
            return strtoupper($matches[0]);
        }

        return null;
    }

    
    public function getDistanceByLatLong($postcode1, $lat1, $lng1)
    {
        // for gettting postcode from lat and lng
        // $postcode2 = $this->getpostcode($lat1, $lng1);
        // $distance = $this->getDistance($postcode1, $postcode2);
        // // dd($distance);
        // return $distance;
        $coordinates = $this->getCoordinates($postcode1);
        if ($coordinates == null) {
            return $coordinates;
        }
        $lat2 = $coordinates['lat'];
        $lng2 = $coordinates['lng'];
        $earthRadius = 6371000; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c; // Distance in meters

        return $distance;
    }

    // public function getpostcode($lat, $lng)
    // {
    //     $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$this->apiKey}";

    //     $response = $this->client->get($url);
    //     $data = json_decode($response->getBody(), true);

    //     if ($data['status'] == 'OK') {
    //         $components = $data['results'][0]['address_components'];
    //         foreach ($components as $component) {
    //             if (in_array('postal_code', $component['types'])) {
    //                 return $component['long_name'];
    //             }
    //         }
    //     }

    //     return null;
    // }
    public function getCoordinates($postcode)
    {
        // dd($this->apiKey);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$postcode}&key={$this->apiKey}";

        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);
        // dd($data);
        if ($data['status'] == 'OK') {
            $location = $data['results'][0]['geometry']['location'];
            return ['lat' => $location['lat'], 'lng' => $location['lng']];
        }

        return null;
    }


}
