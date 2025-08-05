<?php
require_once 'BaseController.php';
class Dashboard extends BaseController {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('Login_model');
        $this->load->model('Settings_model');
    }

    public function index()
    {
        $data['settings'] = $this->Settings_model->get_settings();
        $data['title'] = "IMS | Dashboard";
        $this->load->view('templates/office_modal');
        $this->load->view('templates/reports_modal', $data);
        $this->load->view('templates/header');
        $this->load->view('dashboard');
        $this->load->view('templates/footer');


    }
}