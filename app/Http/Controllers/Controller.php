<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function exchange(Request $request)
    {

        // set API Endpoint, access key, required parameters
        $endpoint = 'convert';
        $access_key = '03c78cede26b9f51820c72f7845df8e1';
        
        $from = 'EUR';
        $to = 'HKD';
        $amount = $request->input("eur");

        // initialize CURL:
        $ch = curl_init('http://api.exchangeratesapi.io/v1/' . $endpoint . '?access_key=' . $access_key . '&from=' . $from . '&to=' . $to . '&amount=' . $amount . '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the JSON data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $conversionResult = json_decode($json, true);

        $conversionResult = '{"success":true,"query":{"from":"GBP","to":"JPY","amount":25},"info":{"timestamp":1519328414,"rate":148.972231},"historical":"","date":"2018-02-22","result":3724.305775}';
        // access the conversion result
        $conversionResult = json_decode($conversionResult, true);

        
        echo $conversionResult['result'];
    }
}