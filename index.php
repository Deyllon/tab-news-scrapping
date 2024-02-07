<?php

require 'vendor/autoload.php';

use Goutte\Client as GoutteClient;
use Twilio\Rest\Client as TwilioClient;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$authToken = $_ENV['TWILIO_AUTH_TOKEN'];
$twilioSid = $_ENV['TWILIO_ACCOUNT_SID'];
$myNumber = $_ENV['MY_NUMBER'];
$twilioNumber = $_ENV['TWILIO_NUMBER'];

$client = new GoutteClient();
$twilioClient = new TwilioClient($twilioSid, $authToken);

$crawler = $client->request('GET', 'https://www.tabnews.com.br/');

$count = 0;

$crawler->filter('body > div > div > .hvRymX > main > ol')->each(function ($node) use ($twilioClient, $myNumber, $twilioNumber) {
   $node->filter('li > article > .cMZbkX > a')->each(function ($node2) use (&$count, $twilioClient, $myNumber, $twilioNumber) {
        if($count <10){
            $message = $twilioClient->messages->create("whatsapp:".$myNumber,[
                'from' => "whatsapp:".$twilioNumber,
                'body' => $node2->text().' aqui está o link:  '.'https://www.tabnews.com.br'.$node2->attr('href')."\n"   
            ]);

            echo "Mensagem enviada com ID: " . $message->sid . "\n";
            $count++;
        }
        
    });
  
});




