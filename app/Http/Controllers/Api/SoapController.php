<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SoapController extends Controller
{
    public function get_soap()
    {

        $word = 'pevaj mi soape lallaalaa';
        $soap_request =

        '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:EdgeTabletAPIIntf-IEdgeTabletAPI">
        <soap:Body>
        <urn:Echo soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
        <AString xsi:type="xsd:string">'.$word.'</AString>
        </urn:Echo>
        </soap:Body>
        </soap:Envelope>';

        $headers = array(
            "Content-type: text/xml",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            //  "SOAPAction: http://url/location/of/soap/method", 
            "Content-length: " . strlen($soap_request),
        );
        $url = "https://server2.edgeanywhere.ca/BeaconTabAPI_QA/EdgeTabletAPISoap.dll/soap/IEdgeTabletAPI";
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $soap_request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $output = curl_exec($ch);

        curl_close($ch);

        print_r($output);
        // echo ("
        //         RESPONSE FROM SERVER :
        //         " . htmlspecialchars($output) . "
        //         ");
    }
}
