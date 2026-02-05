<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Services\MessageBirdService;
use App\Models\InfoBipModel;
use App\Services\DistanceService;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $distanceService;
    protected $messageBird;
    public function __construct(MessageBirdService $messageBird, DistanceService $distanceService)
    {
        $this->messageBird = $messageBird;
        $this->distanceService = $distanceService;
    }
    public function messageBirdSMS($recipient, $message)
    {
        $response = $this->messageBird->sendSMS($recipient, $message);
        return response()->json(['message' => $response]);
    }
    // email bird
    public function messageBirdEmail($recipient, $html, $subject)
    {
        $response = $this->messageBird->sendEmail($recipient, $html, $subject);
        return response()->json(['message' => $response]);
    }


    // infobipMail
    public function InfoBipMail($email,$html,$subject)
    {
        return InfoBipModel::SendEmail($email,$html,$subject);
    }
    
    
    public function postcodeDistance($postcode1, $postcode2)
    {
        $distance = $this->distanceService->getDistance($postcode1, $postcode2);
        if ($distance !== null) {
            return round(($distance  * 0.000621371), 2);
        }
        return "error";
    }

    public function latlongDistance($postcode1, $lat, $long)
    {
        $distance = $this->distanceService->getDistanceByLatLong($postcode1, $lat, $long);
        // meter to miles
        if ($distance !== null) {
            return round(($distance  * 0.000621371), 2);
        }
        return "error";
    }
}
