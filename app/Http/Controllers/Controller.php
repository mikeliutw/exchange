<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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

        $data = ['result' => true, 'data' => $conversionResult['result']];

        return response()->json($data);
    }

    public function history(Request $request)
    {

        // set API Endpoint, access key, required parameters
        $access_key = '03c78cede26b9f51820c72f7845df8e1';

        $base = $request->input("base");
        $symbols = $request->input("symbols");
        $fdate = $request->input("fdate");
        $tdate = $request->input("tdate");

        $Date = $this->getDatesFromRange($fdate, $tdate);

        $datearr = [];
        $ratearr = [];
        $ratevalarr = [];

        $i = 0;
        $temp = 0;

        $arr = ['{"success":true,"timestamp":1642809599,"historical":true,"base":"EUR","date":"2022-01-21","rates":{"HKD":1}}',
            '{"success":true,"timestamp":1642809599,"historical":true,"base":"EUR","date":"2022-01-21","rates":{"HKD":5}}',
            '{"success":true,"timestamp":1642809599,"historical":true,"base":"EUR","date":"2022-01-21","rates":{"HKD":7}}'];

        foreach ($Date as $da) {
            $url = 'http://api.exchangeratesapi.io/v1/' . $da . '?access_key=' . $access_key . '&base=' . $base . '&symbols=' . $symbols . '';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($json, true);

            $ratevalarr[] = $result['rates']['HKD'];

            if ($i == 0) {
                $datearr[] = $result['date'];

            } else {
                $percent = 0;
                $count = count($ratearr);
                $prevalue = $ratevalarr[$count - 1];

                $plusstr = "";
                if ($result['rates']['HKD'] > $prevalue) {
                    $plusstr = "+";

                    $reduct = $result['rates']['HKD'] - $prevalue;

                    $percent = floatval($reduct) / floatval($prevalue) * 100;
                    $percent = number_format($percent, 2);
                } else {
                    $plusstr = "-";
                    $reduct = $prevalue - $result['rates']['HKD'];

                    $percent = floatval($reduct) / floatval($prevalue) * 100;
                    $percent = number_format($percent, 2);
                }

                $datearr[] = $result['date'] . "(" . $plusstr . "" . strval($percent) . "%)";
            }

            $ratearr[] = ($result['rates']['HKD']);

            $i++;

        }

        $data = ['result' => true, 'categories' => $datearr, 'data' => $ratearr];

        return response()->json($data);

    }

    public function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {

        // Declare an empty array
        $array = array();

        $Variable1 = strtotime($start);
        $Variable2 = strtotime($end);

        // Use for loop to store dates into array
        // 86400 sec = 24 hrs = 60*60*24 = 1 day
        for ($currentDate = $Variable1; $currentDate <= $Variable2;
            $currentDate += (86400)) {

            $Store = date($format, $currentDate);
            $array[] = $Store;
        }

        // Return the array elements
        return $array;
    }

}