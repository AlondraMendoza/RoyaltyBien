<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        $datos["nombre"] = "Tania Torres";
        $this->load->view('welcome_message', $datos);
    }

}
