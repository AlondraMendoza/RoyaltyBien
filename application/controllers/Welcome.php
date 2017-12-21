<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        $infoheader["titulo"] = "Royalty Ceramic";
        $this->load->view('template/headerLogin', $infoheader);
        $datos["nombre"] = "Royalty Ceramic";
        $this->load->view('welcome_message', $datos);
        $this->load->view('template/footerLogin', '');
        
    }

}
