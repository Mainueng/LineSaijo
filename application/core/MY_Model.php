<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    public $db2;
    public $tbl_cus_mstr;
    public $tbl_cus_mstr_profile;
    public $tbl_cus_group;
    public $tbl_cus_group_system;
    public $tbl_product;
    public $tbl_product_service;
    public $tbl_product_service_item;
    public $tbl_product_warranty;
    public $tbl_warranty;
    public $tbl_model_category;
    public $tbl_model_description;
    public $tbl_model_error_code;
    public $tbl_model_mstr;
    public $tbl_model_part;
    public $tbl_model_series;
    public $tbl_model_warranty;
    public $tbl_technician;
    public $tbl_user_role;
    public $tbl_jobs;
    public $tbl_job_history;
    public $tbl_job_type;
    public $tbl_job_status;
    public $tbl_job_serial;
    public $tbl_error_code;
    public $tbl_event;
    public $tbl_checkin_event;
    public $tbl_service;
    public $tbl_booking;
    public $tbl_technician_info;
    public $tbl_user_log;
    public $tbl_cus_tech;
    public $tbl_notification;
    public $tbl_warranty_match;
    public $tbl_summary_title;
    public $tbl_summary_header;
    public $tbl_cus_air_mn;
    public $tbl_air_info;
    public $tbl_air_mgr_timer;
    public $tbl_cus_air_cmd;
    public $tbl_air_type;
    public $tbl_energy_hour;
    public $tbl_power_hour;
    public $tbl_symptoms;
    public $tbl_cus_function;
    public $tbl_version;
    public $tbl_saijo_verify;
    public $tbl_manual_title;
    public $tbl_manual_infomation;
    public $tbl_oc_order;
    public $tbl_service_cost;
    public $tbl_service_history;
    public $tbl_invoice;
    public $tbl_job_status_log;
    public $tbl_user_totken;
    public $tbl_dealer_store;
    public $tbl_claim;
    public $tbl_product_sale;

    public function __construct()
    {
        $this->db2 = $this->load->database('auth_db', TRUE);
        $this->db3 = $this->load->database('iot_db', TRUE);

        $this->load->helper('array');
        $this->load->helper('string');

        /***** Table In db *****/
        $this->tbl_cus_mstr = "cus_mstr";
        $this->tbl_cus_mstr_profile = "cus_mstr_profile";
        $this->tbl_cus_group = "cus_group";
        $this->tbl_cus_group_system = "cus_group_system";
        $this->tbl_product = "product";
        $this->tbl_product_service = "product_service";
        $this->tbl_product_service_item = "product_service_item";
        $this->tbl_product_warranty = "product_warranty";
        $this->tbl_warranty = "warranty";
        $this->tbl_model_category = "model_category";
        $this->tbl_model_description = "model_description";
        $this->tbl_model_mstr = "model_mstr";
        $this->tbl_model_part = "model_part";
        $this->tbl_model_series = "model_series";
        $this->tbl_model_warranty = "model_warranty";
        $this->tbl_technician = "technician";
        $this->tbl_user_role = "user_role";
        $this->tbl_service = "service";
        $this->tbl_jobs = "jobs";
        $this->tbl_job_history = "job_history";
        $this->tbl_job_type = "job_type";
        $this->tbl_job_status = "job_status";
        $this->tbl_job_serial = "job_serial";
        $this->tbl_event = "event";
        $this->tbl_checkin_event = "checkin_event";
        $this->tbl_booking = "booking";
        $this->tbl_technician_info = "technician_info";
        $this->tbl_user_log = "user_log";
        $this->tbl_cus_tech = "cus_tech";
        $this->tbl_notification = "notification";
        $this->tbl_error_code = "error_code";
        $this->tbl_warranty_match = "warranty_match";
        $this->tbl_summary_title = "summary_title";
        $this->tbl_summary_header = "summary_header";
        $this->tbl_version = "version";
        $this->tbl_saijo_verify = "saijo_verify";
        $this->tbl_manual_title = "manual_title";
        $this->tbl_manual_information = "manual_information";
        $this->tbl_oc_order = "oc_order";
        $this->tbl_service_cost = "service_cost";
        $this->tbl_service_history = "service_history";
        $this->tbl_invoice = "invoice";
        $this->tbl_job_status_log = "job_status_log";
        $this->tbl_user_token = "user_token";
        $this->tbl_dealer_store = "dealer_store";
        $this->tbl_claim = "claim";
        $this->tbl_product_sale = "product_sale";

        $this->tbl_oc_order = "oc_order";
        $this->tbl_oc_order_product = "oc_order_product";
        $this->tbl_oc_order_option = "oc_order_option";

        /***** Table in db2 *****/


        /***** Table in db3 *****/

        $this->tbl_cus_air_mn = "cus_air_mn";
        $this->tbl_air_info = "air_info";
        $this->tbl_air_mgr_timer = "air_mgr_timer";
        $this->tbl_cus_air_cmd = "cus_air_cmd";
        $this->tbl_air_type = "air_type";
        $this->tbl_energy_hour = "energy_hour";
        $this->tbl_power_hour = "power_hour";
        $this->tbl_symptoms = "symptoms";
        $this->tbl_cus_function = "cus_function";

    }


}
