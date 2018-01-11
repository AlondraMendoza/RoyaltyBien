<?php

//return str_pad((int) $number,$n,"0",STR_PAD_LEFT); Espero te sirva Tania del futuro, Gracias Tania del pasado.
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelocedis extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function BuscarClaveProducto($clave) {
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
        $this->db->from("Productos p");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.IdProductos", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró el producto";
        }
    }

    public function BuscarClaveTarima($clave) {
        $this->db->select("t.IdTarimas");
        $this->db->from("Tarimas t");
        $this->db->where("t.Activo", 1);
        $this->db->where("t.IdTarimas", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró la tarima";
        }
    }

    public function ProductoEnCedis($idproducto) {
        $this->db->select("i.IdInventariosCedis");
        $this->db->from("InventariosCedis i");
        $this->db->where("i.ProductosId", $idproducto);
        $this->db->where("i.Activo", 1);
        $this->db->where("i.FechaSalida", null);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function GuardarProductoCedis($idproducto) {
        $datos = array(
            'CedisId' => 1,
            'FechaEntrada' => date('Y-m-d | h:i:sa'),
            'ProductosId' => $idproducto,
            'UsuariosIdEntrada' => 1,
            'Activo' => 1
        );
        $this->db->insert('InventariosCedis', $datos);
    }

    public function GuardarProductosTarima($idtarima) {
        $this->db->select("d.ProductosId");
        $this->db->from("DetalleTarimas d");
        $this->db->where("d.TarimasId", $idtarima);
        $this->db->where("Activo", 1);
        $filas = $this->db->get();
        $existe = false;
        foreach ($filas->result() as $fila):
            if ($this->ProductoEnCedis($fila->ProductosId)) {
                $existe = true;
                return "Existe";
            }
        endforeach;
        foreach ($filas->result() as $fila):
            $this->GuardarProductoCedis($fila->ProductosId);
        endforeach;
        return "correcto";
    }

}

?>
