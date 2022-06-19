<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class MailPassword
{

    private $api_key = "eeea80d5d0a6c5c1ed34c5523f650d05";
    private $api_key_secret = "e9cadff693ee916b3ae49a1c8097c13d";

    public function send($to_email, $to_name, $subject, $content, $title, $url)
    {
        
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
            [
                'From' => [
                    'Email' => "depthcore.dc@gmail.com",
                    'Name' => "DiY District"
                ],
                'To' => [
                [
                    'Email' => $to_email,
                    'Name' => $to_name
                ]
                ],
                'TemplateID' => 4015966,
                'TemplateLanguage' => true,
                'Subject' => $subject,
                'Variables' => [
                    "title" => $title,
                    "content" => $content,
                    "url" => $url
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

}