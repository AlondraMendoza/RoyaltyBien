<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelousuario extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public static function CategoriasDefectos() {
//        $con = new Conexion();
//        $query = "SELECT c.Nombre,"
//                . "c.IdCatDefectos "
//                . "FROM CategoriasDefectos c "
//                . "WHERE c.Activo=1 ";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarDefectos($cat_id) {
//        $con = new Conexion();
//        $query = "SELECT d.Nombre,"
//                . "d.IdDefectos "
//                . "FROM Defectos d "
//                . "WHERE d.Activo=1 "
//                . "AND d.CatDefectosId=$cat_id";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

}

?>