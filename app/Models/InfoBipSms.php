<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class InfoBipSms extends Model
{
    public static function SendSms($phone,$message)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $response = $client->request('POST',"https://n8w4y2.api.infobip.com/sms/2/text/advanced", [
            'headers' => [
                'Authorization' => 'App a4b0b3a8333a43fd5a5a748f2e8f29ec-aba76354-f1b1-4883-91d6-90503d881214',
                'Accept' => 'application/json',
            ],
            'json' => [
                'messages' => [
                    [
                        'destinations' => [
                            ['to' => $phone],
                        ],
                        'from' => 'ServiceSMS',
                        'text' => $message,
                    ]
                ]
            ]
        ]);
        
        if ($response->getStatusCode() == 200) {
            echo $response->getBody();
        } else {
            echo 'Unexpected HTTP status: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        }
    }
}
