<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp;

class Notification extends Model
{
    public static function SendSms($mobile, $message) {
        $response = null;
        $client = new GuzzleHttp\Client(['base_uri' => env('SMS_URL')]);
        $url = env('SMS_URL') . '?UserName=' . urlencode(env('SMS_USERNAME')) . '&Password=' . urlencode(env('SMS_PASSWORD')) . '&PhoneNumber=' . env('SMS_NUMBER') . '&MessageBody=' . urlencode($message) . '&RecNumber=' . $mobile . '&SmsClass=1';
        try{
            $response = $client->get($url);
        }catch(Exception $e) {
        }
        return $response;
    }
}
