<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require_once("vendor/autoload.php");
class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->helper('directory');


        $this->load->view('welcome_message');

//        $api = directory_map('./controllers/api/');
//        echo date('Y-m-d H:i:s');

//		$this->load->view('api_docs');
        //path/to/project
//        $openapi = \OpenApi\scan($api);
//        header('Content-Type: application/x-yaml');
//        echo $openapi->toYaml();
	}
}
