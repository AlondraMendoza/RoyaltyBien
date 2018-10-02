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
        $fila = $query->row();
        if ($fila != null) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function ObtenerCProductoImportacion($clave) {
        $query = $this->db->query("select IdCProductos from CProductos where ClaveImportacion='$clave'");
        $fila = $query->row();
        if ($fila != null) {
            return $fila->IdCProductos;
        } else {
            return null;
        }
    }

    public function ObtenerProductoImportacion($clave) {
        $query = $this->db->query("select * from Claves where Clave='$clave'");
        $fila = $query->row();
        return $fila;
    }

    public function ObtenerModeloImportacion($clave) {
        $query = $this->db->query("select IdModelos from Modelos where ClaveImportacion='$clave'");
        $fila = $query->row();
        if ($fila != null) {
            return $fila->IdModelos;
        } else {
            return null;
        }
    }

    public function ObtenerColorImportacion($clave) {
        $query = $this->db->query("select IdColores from Colores where ClaveImportacion='$clave'");
        $fila = $query->row();
        if ($fila != null) {
            return $fila->IdColores;
        } else {
            return null;
        }
    }

    public function ObtenerClasificacionImportacion($clave) {
        $query = $this->db->query("select IdClasificaciones from Clasificaciones where ClaveImportacion='$clave'");
        $fila = $query->row();
        if ($fila != null) {
            return $fila->IdClasificaciones;
        } else {
            return null;
        }
    }

}

?>
