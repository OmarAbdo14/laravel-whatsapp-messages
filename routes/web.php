<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Twilio\Rest\Client;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/message', function() {
    // show a form
    return view('message');
});

Route::post('/message', function(Request $request) {
    // TODO: validate incoming params first!

// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
    $sid = getenv("TWILIO_ACCOUNT_SID");
    $token = getenv("TWILIO_AUTH_TOKEN");
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
//        ->create("whatsapp:+18583301932", // to
        // ->create("whatsapp:+201146127749", // to
       ->create("whatsapp:+201009319782", // to

            [
                "from" => "whatsapp:+14155238886",
                "body" => "ذاكري كويس"
            ]
        );

    print($message->sid);
//    $url = "https://messages-sandbox.nexmo.com/v0.1/messages";
//    $params = ["to" => ["type" => "whatsapp", "number" => $request->input('number')],
//        "from" => ["type" => "whatsapp", "number" => "14157386170"],
//        "message" => [
//            "content" => [
//                "type" => "text",
//                "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
//            ]
//        ]
//    ];
//    $headers = ["Authorization" => "Basic " . base64_encode(env('NEXMO_API_KEY') . ":" . env('NEXMO_API_SECRET'))];
//
//    $client = new \GuzzleHttp\Client();
//    $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
//    $data = $response->getBody();
//    Log::Info($data);

    return view('thanks');
});

Route::post('/webhooks/status', function(Request $request) {
    $data = $request->all();
    Log::Info($data);
});

Route::post('/webhooks/inbound', function(Request $request) {
    $data = $request->all();

    $text = $data['message']['content']['text'];
    $number = intval($text);
    Log::Info($number);
    if($number > 0) {
        $random = rand(1, 8);
        Log::Info($random);
        $respond_number = $number * $random;
        Log::Info($respond_number);
        $url = "https://messages-sandbox.nexmo.com/v0.1/messages";
        $params = ["to" => ["type" => "whatsapp", "number" => $data['from']['number']],
            "from" => ["type" => "whatsapp", "number" => "14157386170"],
            "message" => [
                "content" => [
                    "type" => "text",
                    "text" => "The answer is " . $respond_number . ", we multiplied by " . $random . "."
                ]
            ]
        ];
        $headers = ["Authorization" => "Basic " . base64_encode(env('NEXMO_API_KEY') . ":" . env('NEXMO_API_SECRET'))];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
        $data = $response->getBody();
    }
    Log::Info($data);
});
