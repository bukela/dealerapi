<?php

namespace App\Http\Controllers\Api;

use App\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApplicationSoapController extends Controller
{

    public $tracker_id;
    public $id;

    public function app_login($id)
    {

        $this->id = $id;
		
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
            "Accept: text/xml",
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

		$this->tracker_id = getBetween($tracker,'<TrackerID>','</TrackerID>');

		// dd($tracker_id);

		sleep(5);	

		$this->app_token($id,$this->tracker_id);
        
	}

	public function app_token($id,$tracker_id)
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

		$this->app_send($id,$token);
        
	}

    public function app_send($id,$token)
    {

        $app = Application::findOrFail($id);
        $rel = ["general","loan_detail", "employment","previous_employment", "about_equipment", "coapplicant", "previous_address", "home_own","files","current_address"];
        $application = $app->with($rel)->first();

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
            <UserToken>{$token}</UserToken>
            <Request>
                <Operation>SubmitApplication</Operation>
                <SubmitApp>
                <Application>
                    <AppTypeID>2</AppTypeID>
                    <App_Number></App_Number>\n";
                    $soap_request .= "<Rep1ID>948</Rep1ID>\n";
                    !empty($application->about_equipment->program) ?
        $soap_request .= "<CreditProgramID>{$application->about_equipment->program}</CreditProgramID>\n" :
        // $soap_request .= "<CreditProgramID></CreditProgramID>\n" ;
        die('CreditProgramID is required field');
                    !empty($application->type) ? 
        $soap_request .= "<AssetTypeID>{$application->type}</AssetTypeID>\n" :
        // $soap_request .= "<AssetTypeID></AssetTypeID>\n";
        die('AssetTypeID is required field');

        $soap_request .= "<AssetSubTypeID>Motorcycle</AssetSubTypeID>
                    <Equipment_Age>Used</Equipment_Age>
                    <Equipment_Description>Polaris</Equipment_Description>
                    <usr_VehicleYear>2016</usr_VehicleYear>
                    <usr_VehicleOdometer>50000</usr_VehicleOdometer>
                    <usr_VIN></usr_VIN>
                    <Estimated_Equipment_Cost>15000</Estimated_Equipment_Cost>
                    <FundingSourceID>123</FundingSourceID>
                    <Rep1ID>948</Rep1ID>
                    <Vendor_Number>1003982</Vendor_Number>
                    <Source_Number>118</Source_Number>
                </Application>
                <Applicant>\n";
                    !empty($application->general->title) ?
                $soap_request .= "<DBA>{$application->general->title}</DBA>\n" :
                $soap_request .= "<DBA></DBA>\n";
                    !empty($application->first_name) ?
                $soap_request .= "<FirstName>{$application->first_name}</FirstName>\n" :
                // $soap_request .= "<FirstName></FirstName>\n" ;
                die('FirstName is required field');
                    !empty($application->last_name) ?
                $soap_request .= "<LastName>{$application->last_name}</LastName>\n" :
                // $soap_request .= "<LastName></LastName>\n" ;
                die('LastName is required field');
                    !empty($application->general->sin) ?
                $soap_request .= "<Fed_Tax_ID>{$application->general->sin}</Fed_Tax_ID>\n" :
                $soap_request .= "<Fed_Tax_ID></Fed_Tax_ID>\n" ;
                    !empty($application->general->martial_status) ?
                $soap_request .= "<usr_MaritalStatus>{$application->general->martial_status}</usr_MaritalStatus>\n" :
                // $soap_request .= "<usr_MaritalStatus></usr_MaritalStatus>\n" ;
                die('usr_MaritalStatus is required field');
                    !empty($application->general->dependents) ?
                $soap_request .= "<usr_Dependents>{$application->general->dependents}</usr_Dependents>\n" :
                $soap_request .= "<usr_Dependents>{$application->general->dependents}</usr_Dependents>\n" ;
                    !empty($application->general->date_of_birth) ?
                $soap_request .= "<Established>{$application->general->date_of_birth}</Established>\n" :
                // $soap_request .= "<Established></Established>\n" ;
                die('Established is required field');
                    !empty($application->general->home_phone) ?
                $soap_request .= "<Phone>{$application->general->home_phone}</Phone>\n" : 
                // $soap_request .= "<Phone></Phone>\n" ;
                die('Phone is required field');
                    !empty($application->general->mobile_phone) ? 
                $soap_request .= "<Phone2>{$application->general->mobile_phone}</Phone2>\n" :
                // $soap_request .= "<Phone2></Phone2>\n" ;
                die('Phone2 is required field');
                    !empty($application->general->email_address) ?
                $soap_request .= "<EmailAddress>{$application->general->email_address}</EmailAddress>\n" :
                // $soap_request .= "<EmailAddress></EmailAddress>\n" ;
                die('EmailAddress is required field');
                    !empty($application->general->prefered_language) ?
                $soap_request .= "<usr_PreferredLanguage>{$application->general->prefered_language}</usr_PreferredLanguage>\n" :
                $soap_request .= "<usr_PreferredLanguage></usr_PreferredLanguage>\n" ;
                    !empty($application->general->type_of_government_id) ?
                $soap_request .= "<usr_GovernmentIDRef>{$application->general->type_of_government_id}</usr_GovernmentIDRef>\n" :
                $soap_request .= "<usr_GovernmentIDRef></usr_GovernmentIDRef>\n" ;
                    !empty($application->general->government_id_provided) ?
                $soap_request .= "<usr_GovernmentIDNumber>{$application->general->government_id_provided}</usr_GovernmentIDNumber>\n" :
                $soap_request .= "<usr_GovernmentIDNumber></usr_GovernmentIDNumber>\n" ;
                    !empty($application->current_address->address) ?
                $soap_request .= "<Address>{$application->current_address->address}</Address>\n" :
                // $soap_request .= "<Address></Address>\n" ;
                die('Address is required field');
                    !empty($application->current_address->city) ?
                $soap_request .= "<City>{$application->current_address->city}</City>\n" :
                // $soap_request .= "<City></City>\n" ;
                die('City is required field');
                    !empty($application->current_address->province) ?
                $soap_request .= "<County>{$application->current_address->province}</County>\n" :
                // $soap_request .= "<County></County>\n" ;
                die('County is required field');
                    !empty($application->current_address->postal_code) ?
                $soap_request .= "<Zip>{$application->current_address->postal_code}</Zip>\n" :
                // $soap_request .= "<Zip></Zip>\n" ;
                die('Zip is required field');
                    !empty($application->current_address->duration_at_current_location) ?
                $soap_request .= "<usr_YearsAtCurrentAddress>{$application->current_address->duration_at_current_location}</usr_YearsAtCurrentAddress>\n" :
                // $soap_request .= "<usr_YearsAtCurrentAddress></usr_YearsAtCurrentAddress>\n" ;
                die('usr_YearsAtCurrentAddress is required field');
                    !empty($application->previous_address->address) ?
                $soap_request .= "<usr_Address>{$application->previous_address->address}</usr_Address>\n" :
                $soap_request .= "<usr_Address></usr_Address>\n" ;
                    !empty($application->previous_address->address_2) ?
                $soap_request .= "<usr_Address2>{$application->previous_address->address_2}</usr_Address2>\n" :
                $soap_request .= "<usr_Address2></usr_Address2>\n" ;
                    !empty($application->previous_address->city) ?
                $soap_request .= "<usr_City>{$application->previous_address->city}</usr_City>\n" :
                $soap_request .= "<usr_City></usr_City>\n" ;
                    !empty($application->previous_address->province) ?
                $soap_request .= "<usr_Province>{$application->previous_address->province}</usr_Province>\n" :
                $soap_request .= "<usr_Province></usr_Province>\n" ;
                    !empty($application->previous_address->postal_code) ?
                $soap_request .="<usr_PostalCode>{$application->previous_address->postal_code}</usr_PostalCode>\n" :
                $soap_request .="<usr_PostalCode></usr_PostalCode>\n" ;
                    !empty($application->previous_address->duration_at_current_location) ?
                $soap_request .= "<usr_YearsAtLocation>{$application->previous_address->duration_at_current_location}</usr_YearsAtLocation>\n" :
                $soap_request .= "<usr_YearsAtLocation></usr_YearsAtLocation>\n" ;
                    !empty($application->home_own->residential_status) ?
                $soap_request .= "<usr_ResidentialStatus>{$application->home_own->residential_status}</usr_ResidentialStatus>\n" :
                // $soap_request .= "<usr_ResidentialStatus></usr_ResidentialStatus>\n" ;
                die('usr_ResidentialStatus is required field');
                    !empty($application->home_own->title_of_property) ?
                $soap_request .="<usr_TitleOfPropertyListedAbove>{$application->home_own->title_of_property}</usr_TitleOfPropertyListedAbove>\n" :
                // $soap_request .="<usr_TitleOfPropertyListedAbove></usr_TitleOfPropertyListedAbove>\n" ;
                die('usr_TitleOfPropertyListedAbove is required field');
                    !empty($application->home_own->monthly_payment) ?
                $soap_request .= "<usr_MonthlyPayment>{$application->home_own->monthly_payment}</usr_MonthlyPayment>\n" :
                // $soap_request .= "<usr_MonthlyPayment></usr_MonthlyPayment>\n" ;
                die('usr_MonthlyPayment is required field');
                    !empty($application->home_own->payment_made_to) ?
                $soap_request .= "<usr_PaymentMadeToWhom>{$application->home_own->payment_made_to}</usr_PaymentMadeToWhom>\n" :
                $soap_request .= "<usr_PaymentMadeToWhom></usr_PaymentMadeToWhom>\n" ;
                    !empty($application->home_own->outstanding_mortgage_balance) ?
                $soap_request .= "<usr_OutstandingMortgageBalance>{$application->home_own->outstanding_mortgage_balance}</usr_OutstandingMortgageBalance>\n" :
                $soap_request .= "<usr_OutstandingMortgageBalance></usr_OutstandingMortgageBalance>\n" ;

                    !empty($application->employment->employment_status) ?
                $soap_request .= "<usr_EmploymentStatus>{$application->employment->employment_status}</usr_EmploymentStatus>\n" :
                // $soap_request .= "<usr_EmploymentStatus></usr_EmploymentStatus>\n" ;
                die('usr_EmploymentStatus is required field');
                    !empty($application->employment->company_name) ?
                $soap_request .= "<usr_CompanyName>{$application->employment->company_name}</usr_CompanyName>\n" :
                // $soap_request .= "<usr_CompanyName></usr_CompanyName>\n" ;
                die('usr_CompanyName is required field');
                    !empty($application->employment->company_address) ?
                $soap_request .= "<usr_CompanyAddress>{$application->employment->company_address}</usr_CompanyAddress>\n" :
                // $soap_request .= "<usr_CompanyAddress></usr_CompanyAddress>\n" ;
                die('usr_CompanyAddress is required field');
                    !empty($application->employment->company_city) ?
                $soap_request .= "<usr_CompanyCity>{$application->employment->company_city}</usr_CompanyCity>\n" :
                // $soap_request .= "<usr_CompanyCity></usr_CompanyCity>\n" ;
                die('usr_CompanyCity is required field');
                    !empty($application->employment->company_province) ?
                $soap_request .= "<usr_CompanyProvince>{$application->employment->company_province}</usr_CompanyProvince>\n" :
                // $soap_request .= "<usr_CompanyProvince></usr_CompanyProvince>\n" ;
                die('usr_CompanyProvince is required field');
                    !empty($application->employment->company_postal_code) ?
                $soap_request .= "<usr_CompanyPostal>{$application->employment->company_postal_code}</usr_CompanyPostal>\n" :
                $soap_request .= "<usr_CompanyPostal></usr_CompanyPostal>\n" ;
                    !empty($application->employment->company_telephone) ?
                $soap_request .= "<usr_CompanyTelephone>{$application->employment->company_telephone}</usr_CompanyTelephone>\n" :
                // $soap_request .= "<usr_CompanyTelephone></usr_CompanyTelephone>\n" ;
                die('usr_CompanyTelephone is required field');
                    !empty($application->employment->company_type_of_business) ?
                $soap_request .= "<usr_CompanyTypeOfBusiness>{$application->employment->company_type_of_business}</usr_CompanyTypeOfBusiness>\n" :
                // $soap_request .= "<usr_CompanyTypeOfBusiness></usr_CompanyTypeOfBusiness>\n" ;
                die('usr_CompanyTypeOfBusiness is required field');
                    !empty($application->employment->company_salary) ?
                $soap_request .= "<usr_CompanySalary>{$application->employment->company_salary}</usr_CompanySalary>\n" :
                // $soap_request .= "<usr_CompanySalary></usr_CompanySalary>\n" ;
                die('usr_CompanySalary is required field');
                    !empty($application->employment->company_salary_type) ?
                $soap_request .= "<usr_CompanySalaryType>{$application->employment->company_salary_type}</usr_CompanySalaryType>\n" :
                // $soap_request .= "<usr_CompanySalaryType></usr_CompanySalaryType>\n" ;
                die('usr_CompanySalaryType is required field');
                    !empty($application->employment->company_years_of_employment) ?
                $soap_request .= "<usr_CompanyYearsOfEmployment>{$application->employment->company_years_of_employment}</usr_CompanyYearsOfEmployment>\n" :
                // $soap_request .= "<usr_CompanyYearsOfEmployment></usr_CompanyYearsOfEmployment>\n" ;
                die('usr_CompanyYearsOfEmployment is required field');
                    !empty($application->employment->company_months_of_employment) ?
                $soap_request .= "<usr_CompanyMonthsOfEmployment>{$application->employment->company_months_of_employment}</usr_CompanyMonthsOfEmployment>\n" :
                // $soap_request .= "<usr_CompanyMonthsOfEmployment></usr_CompanyMonthsOfEmployment>\n" ;
                die('usr_CompanyMonthsOfEmployment is required field');
                    !empty($application->employment->company_position) ?
                $soap_request .= "<usr_CompanyPosition>{$application->employment->company_position}</usr_CompanyPosition>\n" :
                // $soap_request .= "<usr_CompanyPosition></usr_CompanyPosition>\n" ;
                die('usr_CompanyPosition is required field');
                    !empty($application->previous_employment->previous_company_name) ?
                $soap_request .= "<usr_PreviousCompanyName>{$application->previous_employment->previous_company_name}</usr_PreviousCompanyName>\n" :
                $soap_request .= "<usr_PreviousCompanyName></usr_PreviousCompanyName>\n" ;
                    !empty($application->previous_employment->previous_company_address) ?
                $soap_request .= "<usr_PreviousCompanyAddress>{$application->previous_employment->previous_company_address}</usr_PreviousCompanyAddress>\n" :
                $soap_request .= "<usr_PreviousCompanyAddress></usr_PreviousCompanyAddress>\n" ;
                    !empty($application->previous_employment->previous_company_city) ?
                $soap_request .= "<usr_PreviousCompanyCity>{$application->previous_employment->previous_company_city}</usr_PreviousCompanyCity>\n" :
                $soap_request .= "<usr_PreviousCompanyCity></usr_PreviousCompanyCity>\n" ;
                    !empty($application->previous_employment->previous_company_province) ?
                $soap_request .= "<usr_PreviousCompanyProvince>{$application->previous_employment->previous_company_province}</usr_PreviousCompanyProvince>\n" :
                $soap_request .= "<usr_PreviousCompanyProvince></usr_PreviousCompanyProvince>\n" ;
                    !empty($application->previous_employment->previous_company_postal_code) ?
                $soap_request .= "<usr_PreviousCompanyPostalCode>{$application->previous_employment->previous_company_postal_code}</usr_PreviousCompanyPostalCode>\n" :
                $soap_request .= "<usr_PreviousCompanyPostalCode></usr_PreviousCompanyPostalCode>\n" ;
                    !empty($application->previous_employment->previous_company_telephone) ?
                $soap_request .= "<usr_PreviousCompanyTelephoneNumber>{$application->previous_employment->previous_company_telephone}</usr_PreviousCompanyTelephoneNumber>\n" :
                $soap_request .= "<usr_PreviousCompanyTelephoneNumber></usr_PreviousCompanyTelephoneNumber>\n" ;
                    !empty($application->previous_employment->previous_company_years_of_employment) ?
                $soap_request .= "<usr_PreviousCompanyEmploymentLength>{$application->previous_employment->previous_company_years_of_employment}</usr_PreviousCompanyEmploymentLength>\n" :
                $soap_request .= "<usr_PreviousCompanyEmploymentLength></usr_PreviousCompanyEmploymentLength>\n" ;
                $soap_request .= "</Applicant>\n";
                $soap_request .= "<ContractTerms>\n";
                    !empty($application->loan_detail->down_payment) ?
                $soap_request .= "<DownPayment>{$application->loan_detail->down_payment}</DownPayment>\n" :
                $soap_request .= "<DownPayment></DownPayment>\n" ;
                    !empty($application->loan_detail->trade) ?
                $soap_request .= "<TradeIn>{$application->loan_detail->trade}</TradeIn>\n" :
                $soap_request .= "<TradeIn></TradeIn>\n" ;
                    !empty($application->loan_detail->payment_frequency) ?
                $soap_request .= "<PaymentPlanTypeID>{$application->loan_detail->payment_frequency}</PaymentPlanTypeID>\n" :
                // $soap_request .= "<PaymentPlanTypeID></PaymentPlanTypeID>\n" ;
                die('PaymentPlanTypeID is required field');
                    !empty($application->loan_detail->payment_term) ?
                $soap_request .= "<TermLength>{$application->loan_detail->payment_term}</TermLength>\n" :
                // $soap_request .= "<TermLength></TermLength>\n" ;
                die('TermLength is required field');
                    !empty($application->loan_detail->amort_term) ?
                $soap_request .= "<AmortTerm>{$application->loan_detail->amort_term}</AmortTerm>\n" :
                $soap_request .= "<AmortTerm></AmortTerm>\n" ;
                    !empty($application->loan_detail->defferal_periods) ?
                $soap_request .= "<QtyZeroPayments>{$application->loan_detail->defferal_periods}</QtyZeroPayments>\n" :
                // $soap_request .= "<QtyZeroPayments></QtyZeroPayments>\n" ;
                die('QtyZeroPayments is required field');
                    !empty($application->loan_detail->amount_owing_at_the_end_of_loan_term) ?
                $soap_request .= "<BalloonAmount>{$application->loan_detail->amount_owing_at_the_end_of_loan_term}</BalloonAmount>\n" :
                $soap_request .= "<BalloonAmount></BalloonAmount>\n" ;
                    !empty($application->loan_detail->rate) ?
                $soap_request .= "<PricingSpread>{$application->loan_detail->rate}</PricingSpread>\n" :
                // $soap_request .= "<PricingSpread></PricingSpread>\n" ;
                die('PricingSpread is required field');
                    !empty($application->loan_detail->contract_start_date) ?
                $soap_request .= "<Lease_Start_Date>{$application->loan_detail->contract_start_date}</Lease_Start_Date>\n" :
                // $soap_request .= "<Lease_Start_Date></Lease_Start_Date>\n" ;
                die('Lease_Start_Date is required field');
                    !empty($application->loan_detail->first_payment_date) ?
                $soap_request .= "<FirstPaymentDate>{$application->loan_detail->first_payment_date}</FirstPaymentDate>\n" :
                // $soap_request .= "<FirstPaymentDate></FirstPaymentDate>\n" ;
                die('FirstPaymentDate is required field');
                    !empty($application->loan_detail->payment) ?
                $soap_request .= "<Payment_Amount>{$application->loan_detail->payment}</Payment_Amount>\n" :
                $soap_request .= "<Payment_Amount></Payment_Amount>\n" ;
                $soap_request .= "</ContractTerms>\n";
                $soap_request .= "<CoBorrowers>\n";
                if(!empty($application->coapplicant)) {
                $soap_request .= "<CoBorrower>\n";
                    !empty($application->coapplicant->colessee) ?
                $soap_request .= "<CoLessee>{$application->coapplicant->colessee}</CoLessee>\n" :
                $soap_request .= "<CoLessee></CoLessee>\n" ;
                    !empty($application->coapplicant->first_name) ?
                $soap_request .= "<First_Name>{$application->coapplicant->first_name}</First_Name>\n" :
                $soap_request .= "<First_Name></First_Name>\n" ;
                    !empty($application->coapplicant->last_name) ?
                $soap_request .= "<Last_Name>{$application->coapplicant->last_name}</Last_Name>\n" :
                $soap_request .= "<Last_Name></Last_Name>\n" ;
                    !empty($application->coapplicant->sin) ?
                $soap_request .= "<SSN>{$application->coapplicant->sin}</SSN>\n" :
                $soap_request .= "<SSN></SSN>\n" ;
                    !empty($application->coapplicant->date_of_birth) ?
                $soap_request .= "<DateOfBirth>{$application->coapplicant->date_of_birth}</DateOfBirth>\n" :
                $soap_request .= "<DateOfBirth></DateOfBirth>\n" ;
                    !empty($application->coapplicant->home_phone) ?
                $soap_request .= "<Phone>{$application->coapplicant->home_phone}</Phone>\n" :
                $soap_request .= "<Phone></Phone>\n" ;
                    !empty($application->coapplicant->mobile_phone) ?
                $soap_request .= "<Phone2>{$application->coapplicant->mobile_phone}</Phone2>\n" :
                $soap_request .= "<Phone2></Phone2>\n" ;
                    !empty($application->coapplicant->email_address) ?
                $soap_request .= "<EmailAddress>{$application->coapplicant->email_address}</EmailAddress>\n" :
                $soap_request .= "<EmailAddress></EmailAddress>\n" ;
                    !empty($application->coapplicant->street_name) ?
                $soap_request .= "<Address>{$application->coapplicant->street_name}</Address>\n" :
                $soap_request .= "<Address></Address>\n" ;
                    !empty($application->coapplicant->appt_po) ?
                $soap_request .= "<PO_Box>{$application->coapplicant->appt_po}</PO_Box>\n" :
                $soap_request .= "<PO_Box></PO_Box>\n" ;
                    !empty($application->coapplicant->city) ?
                $soap_request .= "<City>{$application->coapplicant->city}</City>\n" :
                $soap_request .= "<City></City>\n" ;
                    !empty($application->coapplicant->country) ?
                $soap_request .= "<County>{$application->coapplicant->country}</County>\n" :
                $soap_request .= "<County></County>\n" ;
                    !empty($application->coapplicant->postal_code) ?
                $soap_request .= "<Zip>{$application->coapplicant->postal_code}</Zip>\n" :
                $soap_request .= "<Zip></Zip>\n" ;
                    !empty($application->coapplicant->rent_or_own) ?
                $soap_request .= "<usr_RentOrOwn>{$application->coapplicant->rent_or_own}</usr_RentOrOwn>\n" :
                $soap_request .= "<usr_RentOrOwn></usr_RentOrOwn>\n" ;
                    !empty($application->coapplicant->monthly_housing_payment) ?
                $soap_request .= "<MonthlyHousingPayment>{$application->coapplicant->monthly_housing_payment}</MonthlyHousingPayment>\n" :
                $soap_request .= "<MonthlyHousingPayment></MonthlyHousingPayment>\n" ;
                    !empty($application->coapplicant->relationship_to_applicant) ?
                $soap_request .= "<usr_RelationshipToApplicant>{$application->coapplicant->relationship_to_applicant}</usr_RelationshipToApplicant>\n" :
                $soap_request .= "<usr_RelationshipToApplicant></usr_RelationshipToApplicant>\n" ;
                    !empty($application->coapplicant->company_name) ?
                $soap_request .= "<EmployedBy>{$application->coapplicant->company_name}</EmployedBy>\n" :
                $soap_request .= "<EmployedBy></EmployedBy>\n" ;
                    !empty($application->coapplicant->position) ?
                $soap_request .= "<Title>{$application->coapplicant->position}</Title>\n" :
                $soap_request .= "<Title></Title>\n" ;
                    !empty($application->coapplicant->employer_address) ?
                $soap_request .= "<usr_CoBorrowerAddress>{$application->coapplicant->employer_address}</usr_CoBorrowerAddress>\n" :
                $soap_request .= "<usr_CoBorrowerAddress></usr_CoBorrowerAddress>\n" ;
                    !empty($application->coapplicant->employer_phone) ?
                $soap_request .= "<usr_CoBorrowerEmploymentPhoneNo>{$application->coapplicant->employer_phone}</usr_CoBorrowerEmploymentPhoneNo>\n" :
                $soap_request .= "<usr_CoBorrowerEmploymentPhoneNo></usr_CoBorrowerEmploymentPhoneNo>\n" ;
                    !empty($application->coapplicant->number_of_years_employed) ?
                $soap_request .= "<usr_CoBorrowerNumberOfYearsEmployed>{$application->coapplicant->number_of_years_employed}</usr_CoBorrowerNumberOfYearsEmployed>\n" :
                $soap_request .= "<usr_CoBorrowerNumberOfYearsEmployed></usr_CoBorrowerNumberOfYearsEmployed>\n" ;
                    !empty($application->coapplicant->number_of_months_employed) ?
                $soap_request .= "<usr_CoBorrowerNumberOfMonthsEmployed>{$application->coapplicant->number_of_months_employed}</usr_CoBorrowerNumberOfMonthsEmployed>\n" :
                $soap_request .= "<usr_CoBorrowerNumberOfMonthsEmployed></usr_CoBorrowerNumberOfMonthsEmployed>\n" ;
                    !empty($application->coapplicant->gross_monthly_income) ?
                $soap_request .= "<usr_GrossMonthlyIncome>{$application->coapplicant->gross_monthly_income}</usr_GrossMonthlyIncome></CoBorrower>\n" :
                $soap_request .= "<usr_GrossMonthlyIncome></usr_GrossMonthlyIncome></CoBorrower>\n" ;
                }
                $soap_request .= "</CoBorrowers>
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

        $xml = simplexml_load_string($output);

		$namespaces = $xml->getNamespaces(true);

		$tracker = (string) $xml->children($namespaces['SOAP-ENV'])
		->children($namespaces['NS1'])
		->children();

		$this->tracker_id = getBetween($tracker,'<TrackerID>','</TrackerID>');

        // print_r($output);
        sleep(20);
        $this->get_app_number($this->tracker_id, $token);
        
    }

    public function get_app_number($tracker_id, $token) {
// dd($token);
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

		$response = (string) $xml->children($namespaces['SOAP-ENV'])
		->children($namespaces['NS1'])
		->children();

        $app_number = (int) getBetween($response,'<App_Number>','</App_Number>');
        
        \App\Application::find($this->id)->update(['app_number' => $app_number]);
        dd($app_number);


    }

}
