<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inicio extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $datos["nombre"] = "Cadena ejemplo";
        $datos["apellido"] = "Cadena ejemplo 2";
        $this->load->view('capturista/index', $datos);
    }

}
