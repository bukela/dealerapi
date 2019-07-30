<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SoapFileUploadController extends Controller
{
	public $encoded_file;
	public $file_name;
	public $display_name;


	public function file_test(Request $request) {
		$request->validate([
			'file' => 'required'
		]);

		if($request->has('file')) {

			$this->encoded_file = base64_encode(file_get_contents($request->file));
			$jojo = base64_decode($this->encoded_file);
			return response()->download($jojo);
			dd($this->encoded_file);

		}
	}
    public function app_login(Request $request, $app_number)
    {

		$request->validate([
			'file_name' => 'required|string|max:255',
			'file' => 'required'
		]);

		if($request->has('file')) {

			$this->encoded_file = base64_encode(file_get_contents($request->file));

		}

		if($request->has('file_name')) {

			$this->display_name = trim($request->file_name);

			$this->file_name = preg_replace('/\s/', '_', $this->display_name);

		}
		
        $soap_request =

		'
		<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:EdgeTabletAPIIntf-IEdgeTabletAPI">\r
		<soapenv:Header/>\r
		<soapenv:Body>\r
		   <urn:Submit soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">\r
				  <AXML xsi:type="xsd:string">
					  <EdgeAPI>
					 <APIVersion>V2</APIVersion>
					 <RequestType>PROCESS</RequestType>
					 <EdgeFunction>LOGIN</EdgeFunction>
					 <Request>
						 <UserName>cfcuser</UserName>
						 <Password>Password1</Password>
					 </Request>
				 </EdgeAPI>
			 </AXML>\r
		   </urn:Submit>\r
		</soapenv:Body>\r
	 </soapenv:Envelope>
		';

        // dd($soap_request);

        $headers = array(
            "Content-type: text/xml",
            // "Accept: text/xml",
            // "Cache-Control: no-cache",
            // "Pragma: no-cache",
            //  "SOAPAction: http://url/location/of/soap/method", 
            // "Content-length: " . strlen($soap_request),
        );
        $url = "https://server2.edgeanywhere.ca/BeaconTabAPI_QA/EdgeTabletAPISoap.dll/soap/IEdgeTabletAPI";
        // $url = "https://server2.edgeanywhere.ca/BeaconTabAPI_QA/EdgeTabletAPISoap.dll/wsdl/IEdgeTabletAPI";
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

		$xml = simplexml_load_string($output);

		$namespaces = $xml->getNamespaces(true);

		$tracker = (string) $xml->children($namespaces['SOAP-ENV'])
		->children($namespaces['NS1'])
		->children();

		$tracker_id = getBetween($tracker,'<TrackerID>','</TrackerID>');

		// dd($tracker_id);

		sleep(5);	

		$this->app_token($app_number,$tracker_id);
        
	}

	public function app_token($app_number,$tracker_id)
    {
		
        $soap_request =

		'
		<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:EdgeTabletAPIIntf-IEdgeTabletAPI">\r
		<soapenv:Header/>
		<soapenv:Body>
		   <urn:Submit soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			  <AXML xsi:type="xsd:string">
					  <EdgeAPI>
					 <APIVersion>V2</APIVersion>
					 <RequestType>RETRIEVE</RequestType>	
					 <TrackerID>'.$tracker_id.'</TrackerID>
					 <UserToken/>
				 </EdgeAPI>
			  </AXML>
		   </urn:Submit>
		</soapenv:Body>
	 </soapenv:Envelope>
		';


        $headers = array(
            "Content-type: text/xml",
            "Accept: text/xml",
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

		$xml = simplexml_load_string($output);

		$namespaces = $xml->getNamespaces(true);

		$tracker = (string) $xml->children($namespaces['SOAP-ENV'])
		->children($namespaces['NS1'])
		->children();

		$token = getBetween($tracker,'<UserToken>','</UserToken>');
// dd($token);
		$this->file_upload($app_number,$token);
        
	}

    public function file_upload($app_number,$token)
    {
		$soap_request =

		'
		<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:EdgeTabletAPIIntf-IEdgeTabletAPI">\r
		<soapenv:Header/>\r
		<soapenv:Body>\r
		   <urn:Submit soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">\r
			  <AXML xsi:type="xsd:string">
				  <EdgeAPI>
		 <APIVersion>V2</APIVersion>
		 <RequestType>PROCESS</RequestType>
		 <EdgeFunction>SUBMITINFO</EdgeFunction>
		 <UserToken>'.$token.'</UserToken>
		 <Request>
			 <Operation>SubmitFile</Operation>			
			 <Params>
				 <ObjectTypeID>-10103</ObjectTypeID>
				 <KeyValue>'.$app_number.'</KeyValue>
				 <DisplayName>'.$this->display_name.'</DisplayName>
				 <DocumentFileName>'.$this->file_name.'.pdf</DocumentFileName>
			 </Params>					
		 </Request>
		 </EdgeAPI>
			  </AXML>\r
		   </urn:Submit>\r
		</soapenv:Body>\r
	 </soapenv:Envelope>
		';


        $headers = array(
            "Content-type: text/xml",
            "Accept: text/xml",
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

		$xml = simplexml_load_string($output);

		$namespaces = $xml->getNamespaces(true);


		$tracker = (string) $xml->children($namespaces['SOAP-ENV'])
		->children($namespaces['NS1'])
		->children();

		$tracker_id = getBetween($tracker,'<TrackerID>','</TrackerID>');

		sleep(5);

		$this->get_file_id($tracker_id, $token);
	}

	public function get_file_id($tracker_id, $token) {

		$soap_request =

		'
		<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:EdgeTabletAPIIntf-IEdgeTabletAPI">\r
		<soapenv:Header/>
		<soapenv:Body>
		   <urn:Submit soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
			  <AXML xsi:type="xsd:string">
				<EdgeAPI>
				<APIVersion>V2</APIVersion>
				<RequestType>RETRIEVE</RequestType>
				<TrackerID>'.$tracker_id.'</TrackerID>
				<UserToken>'.$token.'</UserToken>
				</EdgeAPI>
			  </AXML>
		   </urn:Submit>
		</soapenv:Body>
	 </soapenv:Envelope>
		';


        $headers = array(
            "Content-type: text/xml",
            "Accept: text/xml",
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

		$xml = simplexml_load_string($output);

		$namespaces = $xml->getNamespaces(true);

		$tracker = (string) $xml->children($namespaces['SOAP-ENV'])
		->children($namespaces['NS1'])
		->children();

		$file_id = getBetween($tracker,'<ObjectFileID>','</ObjectFileID>');
	// var_dump($file_id);
		$this->send_file($token,$file_id);

	}

	public function send_file($token,$file_id) {

		// $encoded_file = encodeFile64('dummy.pdf');
		// from request
		// $image = base64_encode(file_get_contents($request->file('file')->pat‌​h()));
		// dd($encoded_file);

		$soap_request =

		'
		<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:EdgeTabletAPIIntf-IEdgeTabletAPI">
		<soapenv:Header/>
		<soapenv:Body>
		<urn:UploadFileBase64 soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
		<AUserToken xsi:type="xsd:string">'.$token.'</AUserToken>
		<AFileID xsi:type="xsd:int">'.$file_id.'</AFileID>
		<AFile xsi:type="xsd:string">'.$this->encoded_file.'</AFile>
		</urn:UploadFileBase64>
		</soapenv:Body>
		</soapenv:Envelope>
		';


        $headers = array(
            "Content-type: text/xml",
            "Accept: text/xml",
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

		$xml = simplexml_load_string($output);

		$namespaces = $xml->getNamespaces(true);

		$message = (string) $xml->children($namespaces['SOAP-ENV'])
		->children($namespaces['NS1'])
		->children();


		if($message == "Success") {

			response()->json('File uploaded')->send();

		} else {

			response()->json('File not uploaded')->send();

		}
		

	}
}
