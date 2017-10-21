<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelocapturista extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public static function ListarHornos() {
//        $con = new Conexion();
//        $query = "SELECT Hornos.* FROM Hornos where Activo=1";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarProductos() {
//        $con = new Conexion();
//        $query = "SELECT CProductos.* FROM CProductos where Activo=1 and Nombre != 'Accesorios'";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarCarros() {
//        $con = new Conexion();
//        $query = "SELECT Carros.* FROM Carros where Activo=1";
//        //falta agregar la sucursal
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarModelos($id) {
//        $con = new Conexion();
//        $query = "SELECT m.Nombre, CPM.Imagen, m.IdModelos from CProductos as p join CProductosModelos "
//                . "as CPM on p.IdCProductos=CPM.CProductosId join Modelos as m on CPM.ModelosId=m.IdModelos "
//                . "where p.Activo=1 and p.IdCProductos=$id";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

    public static function ListarColores($id) {
//        $con = new Conexion();
//        $query = "SELECT c.Nombre, c.Descripcion, c.IdColores from Colores as c join ModelosColores
//        as MC on c.IdColores=MC.ColoresId join Modelos as m on MC.ModelosId=m.IdModelos
//        where c.Activo=1 and m.IdModelos=$id";
//        $datos = $con->Consultar($query);
//        $con->Cerrar();
//        return $datos;
    }

}

?>