<?php

//return str_pad((int) $number,$n,"0",STR_PAD_LEFT); Espero te sirva Tania del futuro, Gracias Tania del pasado.
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelosclasificador extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function GuardarDevolucion($cliente, $motivo, $responsable) {
        $datos = array(
            'Cliente' => $cliente,
            'Motivo' => $motivo,
            'Responsable' => $responsable,
            'UsuarioCapturaId' => IdUsuario(),
            'Activo' => 1,
            'FechaCaptura' => date('Y-m-d | h:i:sa')
        );
        $this->db->insert("Devoluciones", $datos);
        return $this->db->insert_id();
    }

    public function GuardarDetalleDevolucion($idproducto, $iddevolucion) {
        if ($this->VerificarProductoDevolucion($idproducto) == "no existe") {
            //Historial captura
            $HistorialCaptura = array(
                'UsuariosId' => IdUsuario(),
                'MovimientosProductosId' => 16,
                'Activo' => 1,
                'ProductosId' => $idproducto,
                'Fecha' => date('Y-m-d | h:i:sa')
            );
            $this->db->insert('HistorialProducto', $HistorialCaptura);
            /* Fin captura historial */
            $datos = array(
                'ProductosId' => $idproducto,
                'Activo' => 1,
                'Procesado' => 0,
                'DevolucionesId' => $iddevolucion
            );
            $this->db->insert("DetalleDevoluciones", $datos);
            return $this->db->insert_id();
        } else {
            return "existe";
        }
    }

    public function VerificarProductoTarima($idproducto) {
        $this->db->select("p.IdProductos");
        $this->db->from("DetalleTarimas d");
        $this->db->join("Tarimas t", "t.IdTarimas=d.TarimasId");
        $this->db->join("Productos p", "p.IdProductos=d.ProductosId");
        $this->db->where("d.Activo", 1);
        $this->db->where("t.Activo", 1);
        $this->db->where("p.IdProductos", $idproducto);
        $this->db->where("t.FechaApertura", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return "existe";
        } else {
            return "no existe";
        }
    }

    public function VerificarProductoDevolucion($idproducto) {
        $this->db->select("p.IdProductos");
        $this->db->from("DetalleDevoluciones d");
        $this->db->join("Devoluciones t", "t.IdDevoluciones=d.DevolucionesId");
        $this->db->join("Productos p", "p.IdProductos=d.ProductosId");
        $this->db->where("d.Activo", 1);
        $this->db->where("t.Activo", 1);
        $this->db->where("p.IdProductos", $idproducto);
        $this->db->where("d.Procesado", 0);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return "existe";
        } else {
            return "no existe";
        }
    }

}

?>
