<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class InfoBipModel
{
    public static function SendEmail($email,$html,$subject)
    {
        $client = new Client([ 
            'verify' => false, // Set 'verify' to false here
        ]);

        $response = $client->request('POST',"https://n8w4y2.api.infobip.com/email/3/send", [
            'headers' => [
                'Authorization' => 'App a4b0b3a8333a43fd5a5a748f2e8f29ec-aba76354-f1b1-4883-91d6-90503d881214',
                'Accept' => 'application/json',
            ],
            'multipart' => [
                [
                    'name' => 'from',
                    'contents' => 'PM247 Availability <availability@available.pm247engineers.co.uk>'
                ],
                [
                    'name' => 'subject',
                    'contents' => $subject
                ],
                [
                    'name' => 'cc',
                    'contents' => 'nealmartinpm247@gmail.com'
                ],
                [
                    'name' => 'to',
                    'contents' => json_encode([
                        'to' => $email,
                        'placeholders' => [
                            'firstName' => ' '
                        ]
                    ])
                ],
                [
                    'name' => 'html',
                    'contents' => $html
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
