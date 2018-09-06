<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modeloventas extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function ObtenerModelo($id) {
        $query = $this->db->query("select * from Modelos where IdModelos=$id");
        return $query->row();
    }

}

?>
