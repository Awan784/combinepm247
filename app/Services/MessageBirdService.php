<?php

namespace App\Services;

use GuzzleHttp\Client;
use MessageBird\Objects\Message;

class MessageBirdService
{

    public function sendSMS($recipient, $message)
    {
        $client = new Client([
            'verify' => false,
        ]);

        // MessageBird API key
        $apiKey = config('messagebird.api_key'); // Make sure this key is set in your .env file as MESSAGEBIRD_API_KEY

        // API endpoint for sending SMS via MessageBird
        $url = 'https://rest.messagebird.com/messages';

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'AccessKey ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'recipients' => $recipient,
                    'originator' => 'PM247', // Replace with your sender name
                    'body' => $message,
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return response()->json(['message' => 'Message sent successfully!', 'response' => $responseBody], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    public function sendEmail($recipientEmail, $html, $subject)
    {   
        // dd($subject);
        $client = new Client([
            'verify' => false,
        ]);

        $apiKey = config('messagebird.email_api_key');  
        // API endpoint for sending email via MessageBird
        $url = "https://api.bird.com/workspaces/83ef8a1e-de39-4058-b836-7294039d9a48/channels/7eea8667-bc99-57b6-8166-67e81f60fc07/messages";
        $contacts = [
            [
                'identifierKey' => 'emailaddress',
                'identifierValue' => $recipientEmail,
            ]
        ];
        if ($recipientEmail != "contracts@pm247.co.uk") {
            $contacts[] = [
                'identifierKey' => 'emailaddress',
                'identifierValue' => 'contracts@pm247.co.uk',
            ];
        }
        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'AccessKey ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'receiver' => [
                        'contacts' => $contacts,
                    ],
                    
        'body' => [
            'type' => 'html',
            'html' => [
                'html' => $html, // Your email body in HTML
                'metadata' => [
                    'subject' => $subject // Set the email subject here
                ]
            ]
        ],
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return response()->json(['message' => 'Email sent successfully!', 'response' => $responseBody], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
