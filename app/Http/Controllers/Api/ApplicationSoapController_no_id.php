<?php

namespace App\Http\Controllers\Api;

use DOMDocument;
use App\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ApplicationSoapController_no_id extends Controller
{

	public function app_login()
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

		$this->app_token($tracker_id);
        
	}

	public function app_token($tracker_id)
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

		$token = getBetween($tracker,'<UserToken>','</UserToken>');

		$this->app_send($token);
        
	}
	

    public function app_send($token)
    {

        // response()->json('errorrrrr',422)->send(); die();
        $soap_request =

        "<soapenv:Envelope xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:urn='urn:EdgeTabletAPIIntf-IEdgeTabletAPI'>
        <soapenv:Header/>
        <soapenv:Body>
      <urn:Submit soapenv:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'>
         <AXML xsi:type='xsd:string'>
         		<EdgeAPI>
				<APIVersion>V2</APIVersion>
				<RequestType>PROCESS</RequestType>
				<EdgeFunction>SUBMITINFO</EdgeFunction>
				<UserToken>".$token."</UserToken>
				<Request>  
				  		<Operation>SubmitApplication</Operation>  
						<SubmitApp>				  			
				  			<!--The Edge Application Wizard Fields Spreadsheet Contains All The Available Field Values--> 
				  			<Application>				 			
					 						 <!--Equipment Type-->
					 			<!--Required--> <AppTypeID>2</AppTypeID> <!--Consumer Application ID-->
					 			<!--Required--> <App_Number></App_Number> <!--Empty For Initial Submission--> <!--Insert App Number For Resubmission-->					 						
								<!--Required--> <CreditProgramID>184</CreditProgramID> <!--ProgramID-->
								<!--Required--> <AssetTypeID>Recreational Vehicles</AssetTypeID>
								<!--Required--> <AssetSubTypeID>Motorcycle</AssetSubTypeID>
								<!--Required--> <Equipment_Age>Used</Equipment_Age> <!--New, Used-->
								<!--Required--> <Equipment_Description>Polaris</Equipment_Description>
											 <usr_VehicleYear>2018</usr_VehicleYear>
											 <usr_VehicleOdometer>50000</usr_VehicleOdometer> <!--Kilometers-->
											 <usr_VIN></usr_VIN>
								<!--Required--> <Estimated_Equipment_Cost>12000.00</Estimated_Equipment_Cost> <!--Equipment Cost-->									  			
					  			<!--Required--> <FundingSourceID>123</FundingSourceID> <!--Clear Financial Corporation Lender ID-->
					  			<!--Required--> <Rep1ID>948</Rep1ID> <!--Merchant Rep ID--> <!--CFC User ID 948-->
								  			 <!--<DataEntryID>948</DataEntryID>--> <!--Data Entry ID--> <!--If Different User From Merchant Rep ID-->				
											 <Vendor_Number>1003982</Vendor_Number> <!--Merchant Number-->
											 <Source_Number>118</Source_Number> <!--Broker Number-->								
							</Application>					
							<Applicant>
											 <!--General Information:-->
											 <DBA></DBA> <!--Title-->
								<!--Required--> <FirstName>Extraordinary</FirstName>
								<!--Required--> <LastName>Boo</LastName>
											 <Fed_Tax_ID></Fed_Tax_ID> <!--<SIN>SIN Number</SIN>-->
								<!--Required--> <usr_MaritalStatus>Single</usr_MaritalStatus>			
											 <usr_Dependents>2</usr_Dependents>
								<!--Required--> <Established>8/20/1964</Established> <!--<DOB>m/d/yyyy</DOB>-->
								<!--Required--> <Phone>06600009788</Phone>
								<!--Required--> <Phone2>6699999955</Phone2>
								<!--Required--> <EmailAddress>test1222@test.com</EmailAddress>
											 <usr_PreferredLanguage>English</usr_PreferredLanguage>
											 <usr_GovernmentIDRef>Drivers_License</usr_GovernmentIDRef>
											 <usr_GovernmentIDNumber></usr_GovernmentIDNumber>
											 <!--Current Address-->
								<!--Required--> <Address>95 COOK DR</Address>
								<!--Required--> <City>POTTAGEVILLE</City>			
								<!--Required--> <County>ON</County> <!--<Province>Ontario</Province>-->
								<!--Required--> <Zip>L0G1T0</Zip>
								<!--Required--> <usr_YearsAtCurrentAddress>10</usr_YearsAtCurrentAddress>
											 <!--Previous Address if < 2 years at current-->
											 <usr_Address>345 Maple Ave</usr_Address>
											 <usr_Address2></usr_Address2>
											 <usr_City>Burlington</usr_City>
											 <usr_Province>ON</usr_Province>
											 <usr_PostalCode>L7S3H4</usr_PostalCode>
											 <usr_YearsAtLocation>10</usr_YearsAtLocation>
											 <!--Home Ownership Details-->										
								<!--Required--> <usr_ResidentialStatus>Own</usr_ResidentialStatus> <!--Rent, Own-->
								<!--Required--> <usr_TitleOfPropertyListedAbove>I/We on Title</usr_TitleOfPropertyListedAbove>
								<!--Required--> <usr_MonthlyPayment>1000.00</usr_MonthlyPayment>
											 <usr_PaymentMadeToWhom>BMO</usr_PaymentMadeToWhom>
											 <usr_OutstandingMortgageBalance></usr_OutstandingMortgageBalance>
											 <!--Employment - Current-->
								<!--Required--> <usr_EmploymentStatus>Employed - Full-Time</usr_EmploymentStatus>
								<!--Required--> <usr_CompanyName>XYZ Corp</usr_CompanyName>
								<!--Required--> <usr_CompanyAddress>1312 Brant St.</usr_CompanyAddress>
								<!--Required--> <usr_CompanyCity>Burlington</usr_CompanyCity>
								<!--Required--> <usr_CompanyProvince>ON</usr_CompanyProvince>
											 <usr_CompanyPostal>L7S4G1</usr_CompanyPostal>
								<!--Required--> <usr_CompanyTelephone>9056815454</usr_CompanyTelephone>
								<!--Required--> <usr_CompanyTypeOfBusiness>Manufacturing</usr_CompanyTypeOfBusiness>
								<!--Required--> <usr_CompanySalary>5000.00</usr_CompanySalary>
								<!--Required--> <usr_CompanySalaryType>Monthly</usr_CompanySalaryType>
								<!--Required--> <usr_CompanyYearsOfEmployment>10</usr_CompanyYearsOfEmployment>
								<!--Required--> <usr_CompanyMonthsOfEmployment>6</usr_CompanyMonthsOfEmployment>
								<!--Required--> <usr_CompanyPosition>Manager</usr_CompanyPosition>
											 <!--Previous Employment if < 2 Years-->
											 <usr_PreviousCompanyName>Harvester Corp</usr_PreviousCompanyName>
											 <usr_PreviousCompanyAddress>399 Harvester Rd</usr_PreviousCompanyAddress>
											 <usr_PreviousCompanyCity>Burlington</usr_PreviousCompanyCity>
											 <usr_PreviousCompanyProvince>ON</usr_PreviousCompanyProvince>
											 <usr_PreviousCompanyPostalCode>L7R2G4</usr_PreviousCompanyPostalCode>
											 <usr_PreviousCompanyTelephoneNumber>9057778888</usr_PreviousCompanyTelephoneNumber>
											 <usr_PreviousCompanyEmploymentLength>5</usr_PreviousCompanyEmploymentLength> <!--Previous Employment Length In Years-->		
							</Applicant>							
							<ContractTerms>
				 							 <!--Loan Details-->
				 							 <DownPayment></DownPayment> <!--Down Payment-->
											 <TradeIn></TradeIn> <!--Net Trade-In Allowance-->
								<!--Required--> <PaymentPlanTypeID>Monthly</PaymentPlanTypeID> <!--Payment Frequency-->
								<!--Required--> <TermLength>60</TermLength> <!--Payment Term-->
											 <AmortTerm></AmortTerm> <!--Amort Term If Different From Payment Term-->
								<!--Required--> <QtyZeroPayments>0</QtyZeroPayments> <!--Deferral Periods-->
											 <BalloonAmount></BalloonAmount> <!--Amount Owing At End Of Loan Term-->						
				 				<!--Required--> <PricingSpread>17.99</PricingSpread> <!--Interest Rate-->			 				
				 				<!--Required--> <Lease_Start_Date>4/30/2019</Lease_Start_Date> <!--Contract Start Date-->
				 				<!--Required--> <FirstPaymentDate>5/30/2019</FirstPaymentDate> <!--First Payment Date-->							
											 <Payment_Amount>162.73</Payment_Amount> <!--Payment-->
							</ContractTerms>						
							<CoBorrowers>
								<CoBorrower>
										 	 <!--General Information-->
								<!--Required--> <CoLessee>Y</CoLessee>		
								<!--Required--> <First_Name>CHRIS</First_Name>
								<!--Required--> <Last_Name>MLITWO</Last_Name>
											 <SSN></SSN> <!--<SIN>SIN Number</SIN>-->
								<!--Required--> <DateOfBirth>6/17/1958</DateOfBirth> <!--<DOB>m/d/yyyy</DOB>-->							
								<!--Required--> <Phone>9056315254</Phone>
											 <Phone2></Phone2>
											 <EmailAddress>test2@test.com</EmailAddress>
								<!--Required--> <Address>24 MIRANDA</Address> <!--Address1-->
											 <PO_Box></PO_Box> <!--Address 2-->
								<!--Required--> <City>TORONTO</City>
								<!--Required--> <County>ON</County> <!--Province-->
								<!--Required--> <Zip>M6K1N2</Zip>
								<!--Required--> <usr_RentOrOwn>Rent</usr_RentOrOwn> <!--Rent, Own-->
								<!--Required--> <MonthlyHousingPayment>600.00</MonthlyHousingPayment> 
								<!--Required--> <usr_RelationshipToApplicant>Other</usr_RelationshipToApplicant> <!--Married, Common-Law, Other--> <!--Required Field For Scoring CoBorrower-->
											 <!--Current Employment-->
								<!--Required--> <EmployedBy>ABC Corp</EmployedBy>
								<!--Required--> <Title>Supervisor</Title>	
								<!--Required--> <usr_CoBorrowerAddress>123 Lakeshore Rd</usr_CoBorrowerAddress>
								<!--Required--> <usr_CoBorrowerEmploymentPhoneNo>9054445555</usr_CoBorrowerEmploymentPhoneNo>
								<!--Required--> <usr_CoBorrowerNumberOfYearsEmployed>5</usr_CoBorrowerNumberOfYearsEmployed>
								<!--Required--> <usr_CoBorrowerNumberOfMonthsEmployed>3</usr_CoBorrowerNumberOfMonthsEmployed>
								<!--Required--> <usr_GrossMonthlyIncome>56000</usr_GrossMonthlyIncome>		
								</CoBorrower>
							</CoBorrowers>						
						</SubmitApp>
				</Request>
			</EdgeAPI>    
         </AXML>
      </urn:Submit>
   </soapenv:Body>
        </soapenv:Envelope>";

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

        print_r($output);
        
	}
	
	
	
	

}
