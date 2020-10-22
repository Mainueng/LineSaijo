<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';


class Payment extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();

        $this->load->library('Authorization_Token');
        $this->load->library('C2P2');

        $this->load->model($this->jobs_model, 'jobs');

        $this->methods['payment_token_post']['limit'] = 500;
        $this->methods['inquiry_payment_post']['limit'] = 500;

    }

    public function payment_token_post()
    {

        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $this->form_validation->set_rules('amount', 'Amount', 'trim');

                if ($this->form_validation->run() == TRUE) {

                    $amount = number_format((float)$this->post('amount'),2, '.', '');

                    $amount_exp = explode('.', $amount);

                    $amount_pad = str_pad($amount_exp[0].$amount_exp[1], 12, "0", STR_PAD_LEFT);
                    
                    $job_id = $this->get('id'); 

                    $this->jobs->setJobId($job_id);

                    $job_type = $this->jobs->job_des();

                    $payment_token = $this->c2p2->payment_token($amount_pad,$job_id,ucfirst($job_type));

                    if ($payment_token) {
                        $http_code = REST_Controller::HTTP_OK;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => 'Success',
                            'data' => array(
                                'payment_token' => $payment_token
                            )
                        );

                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Invalid Signature.",
                            'data' =>  array()
                        );
                    }

                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => trim(strip_tags(validation_errors(),'\n')),
                        'data' => array()
                    );
                }

            } else {
                $http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
                $message = array(
                  'status' => false,
                  'code' => $http_code,
                  'message' => "You do not have permission to access.",
                  'data' => array()
              );
            }

        } else {

            $http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
            $message = array(
              'status' => false,
              'code' => $http_code,
              'message' => $this->_authen_message,
              'data' => array()
          );

        }

        $this->response($message, $http_code);
    }

    public function inquiry_payment_post()
    {

        if ($this->is_authen()) {

            $token_info = $this->authorization_token->userData();

            if ($token_info->user_role_id == 4 || $token_info->user_role_id == 1 || $token_info->user_role_id == 2) {

                $this->form_validation->set_rules('transaction_id', 'Transaction ID', 'trim');

                if ($this->form_validation->run() == TRUE) {

                    $transaction_id = $this->post('transaction_id');

                    $inquiry_payment = $this->c2p2->inquiry_payment($transaction_id);

                    if ($inquiry_payment && $inquiry_payment['resp_code'] == "000") {

                        $job_id = $this->get('id');

                        $this->jobs->setJobId($job_id);

                        $service_fee = $this->jobs->getServiceFee();

                        if ($service_fee) {
                            $invoice_id = $this->jobs->getLastInvoice();

                            $this->jobs->setInvoice($invoice_id);

                            $data = $this->jobs->updateInvoice();

                            $this->jobs->payment_success();
                        } else {
                            $data = true;

                            $this->jobs->payment_success();
                        }

                        if ($data !== false && !is_blank($data)) {

                            $http_code = REST_Controller::HTTP_OK;
                            $message = array(
                                'status' => true,
                                'code' => $http_code,
                                'message' => "Success",
                                'data' => array()
                            );

                        } else {
                            $http_code = REST_Controller::HTTP_BAD_REQUEST;
                            $message = array(
                                'status' => false,
                                'code' => $http_code,
                                'message' => 'Update invoice fail.',
                                'data' => array()
                            );
                        }

                    } elseif ($inquiry_payment && $inquiry_payment['resp_code'] != "000") {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => true,
                            'code' => $http_code,
                            'message' => $inquiry_payment['respDesc'],
                            'data' => array()
                        );

                    } else {
                        $http_code = REST_Controller::HTTP_BAD_REQUEST;
                        $message = array(
                            'status' => false,
                            'code' => $http_code,
                            'message' => "Transaction id not found.",
                            'data' => array()
                        );
                    }

                } else {

                    $http_code = REST_Controller::HTTP_BAD_REQUEST;
                    $message = array(
                        'status' => false,
                        'code' => $http_code,
                        'message' => trim(strip_tags(validation_errors(),'\n')),
                        'data' => array()
                    );
                }

            } else {
                $http_code = REST_Controller::HTTP_METHOD_NOT_ALLOWED;
                $message = array(
                  'status' => false,
                  'code' => $http_code,
                  'message' => "You do not have permission to access.",
                  'data' => array()
              );
            }

        } else {

            $http_code = REST_Controller::HTTP_REQUEST_TIMEOUT;
            $message = array(
              'status' => false,
              'code' => $http_code,
              'message' => $this->_authen_message,
              'data' => array()
          );

        }

        $this->response($message, $http_code);
    }
}