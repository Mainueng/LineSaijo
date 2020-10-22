<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require "2C2P/utils/PaymentGatewayHelper.php";
require "2C2P/enum/APIEnvironment.php";
require "2C2P/model/SignatureError.php";

/*require "enum/CardSecureMode.php";
require "enum/PaymentChannel.php";
*/

class C2P2
{

	public function payment_token($amount,$job_id,$job_type) {

		//$api_env = APIEnvironment::SANDBOX . "/paymentToken";
		$api_env = APIEnvironment::PRODUCTION . "/paymentToken"; 

		$api_version = "10.01"; 
		$nonce_str = uniqid('', true);  

		$mid = "764764000000569";
		//$secret_key = "8uBHVDhv5H5K";
		$secret_key = "FEB78FCC390A044223EDB13BC425B6E3BCE3D01798FBA7A389003858480A1049";


		$desc = "Saijo Denki E-Service - Job ID ".$job_id.' : '.$job_type; 
		$invoice_no = time(); 
		$currency_code = "THB"; 

		$payment_token_request = new stdClass();
		$payment_token_request->version = $api_version;
		$payment_token_request->merchantID = $mid;
		$payment_token_request->invoiceNo = $invoice_no;
		$payment_token_request->desc = $desc;
		$payment_token_request->amount = $amount;
		$payment_token_request->currencyCode = $currency_code;
		$payment_token_request->nonceStr = $nonce_str;

		$pgw_helper = new PaymentGatewayHelper(); 
		$hashed_signature = $pgw_helper->generateSignature($payment_token_request, $secret_key); 
		$payment_token_request->signature = $hashed_signature;

		$encoded_payment_token_response = $pgw_helper->requestAPI($api_env, $payment_token_request);

		$is_valid_signature = $pgw_helper->validateSignature($encoded_payment_token_response, $secret_key);
		if($is_valid_signature) {

			$payment_token_response = $pgw_helper->parseAPIResponse($encoded_payment_token_response);
			$payment_token = $payment_token_response->paymentToken;

			return $payment_token;
		} else {
			return false;
		}

	}

	public function inquiry_payment($transaction_id) {
		//$api_env = APIEnvironment::SANDBOX . "/paymentInquiry";
		$api_env = APIEnvironment::PRODUCTION . "/paymentInquiry"; 

		$api_version = "1.1";

		$mid = "764764000000569";
		//$secret_key = "8uBHVDhv5H5K";
		$secret_key = "FEB78FCC390A044223EDB13BC425B6E3BCE3D01798FBA7A389003858480A1049";

		$payment_inquiry_request = new stdClass();
		$payment_inquiry_request->version = $api_version;
		$payment_inquiry_request->merchantID = $mid;
		$payment_inquiry_request->transactionID = $transaction_id;

		$pgw_helper = new PaymentGatewayHelper(); 
		$hashed_signature = $pgw_helper->generateSignature($payment_inquiry_request, $secret_key);
		$payment_inquiry_request->signature = $hashed_signature;

		$encoded_payment_inquiry_response = $pgw_helper->requestAPI($api_env, $payment_inquiry_request); 

		$is_valid_signature = $pgw_helper->validateSignature($encoded_payment_inquiry_response, $secret_key);
		if($is_valid_signature) {

			$payment_inquiry_response = $pgw_helper->parseAPIResponse($encoded_payment_inquiry_response);
			$resp_code = $payment_inquiry_response->respCode;

			$data = array(
				'resp_code' => $payment_inquiry_response->respCode,
				'respDesc' => $payment_inquiry_response->respDesc
			);

			return $data;

		} else {
			return false;
		}
	}

	public function payment_response($encoded_payment_response) {

		//$secret_key = "8uBHVDhv5H5K";
		$secret_key = "FEB78FCC390A044223EDB13BC425B6E3BCE3D01798FBA7A389003858480A1049";

		$encoded_payment_response = urldecode($encoded_payment_response);

		$pgw_helper = new PaymentGatewayHelper(); 

		$is_valid_signature = $pgw_helper->validateSignature($encoded_payment_response, $secret_key);

		if($is_valid_signature) {

			$payment_response = $pgw_helper->parseAPIResponse($encoded_payment_response);

			$data = array(
				"invoice_no" => $payment_response->invoiceNo,
				"resp_code" => $payment_response->respCode
			);

			return $data;

		} else {
			return false;
		}

	}

}

?>