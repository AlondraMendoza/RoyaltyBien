<?php

//return str_pad((int) $number,$n,"0",STR_PAD_LEFT); Espero te sirva Tania del futuro, Gracias Tania del pasado.
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modeloclasificador extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function ListaHornos($dia) {
        $this->db->select('*');
        $this->db->from('Productos p');
        $this->db->join('Hornos h', 'h.idhornos=p.hornosid');
        $this->db->where('DATE(p.FechaQuemado)', $dia);
        $this->db->where('h.Activo', 1);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.Clasificado', 0);
        $this->db->group_by('h.IdHornos');
        $query = $this->db->get();
        return $query;
    }

    public static function FechaIngles($date) {
        if ($date) {
            $fecha = $date;
            $hora = "";

            # separamos la fecha recibida por el espacio de separación entre
            # la fecha y la hora
            $fechaHora = explode(" ", $date);
            if (count($fechaHora) == 2) {
                $fecha = $fechaHora[0];
                $hora = $fechaHora[1];
            }

            # cogemos los valores de la fecha
            $values = preg_split('/(\/|-)/', $fecha);
            if (count($values) == 3) {
                # devolvemos la fecha en formato ingles
                if ($hora && count(explode(":", $hora)) == 3) {
                    # si la hora esta separada por : y hay tres valores...
                    $hora = explode(":", $hora);
                    return date("Ymd H:i:s", mktime($hora[0], $hora[1], $hora[2], $values[1], $values[0], $values[2]));
                } else {
                    return date("Ymd", mktime(0, 0, 0, $values[1], $values[0], $values[2]));
                }
            }
        }
        return "";
    }

    public function ProductosPendientesHornos($dia, $horno) {
        $this->db->select('h.*');
        $this->db->from('Productos p');
        $this->db->join('Hornos h', 'h.idhornos=p.hornosid');
        $this->db->where('DATE(p.FechaQuemado)', $dia);
        $this->db->where('h.Activo', 1);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.Clasificado', 0);
        $this->db->where('p.HornosId', $horno);
        $this->db->group_by('p.IdProductos');
        $cuantos = $this->db->get()->num_rows();
        return $cuantos;
    }

    public function ListaProductos($dia, $horno) {
        $this->db->select('*');
        $this->db->from('Productos p');
        $this->db->join('CProductos cp', 'cp.IdCProductos=p.CProductosId');
        $this->db->where('DATE(p.FechaQuemado)', $dia);
        $this->db->where('cp.Activo', 1);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.HornosId', $horno);
        $this->db->where('p.Clasificado', 0);
        $this->db->group_by('p.CProductosId');
        $query = $this->db->get();
        return $query;
    }

    public function ProductosPendientesCproductos($dia, $horno, $cprod) {
        $this->db->select('*');
        $this->db->from('Productos p');
        $this->db->join('CProductos cp', 'cp.IdCProductos=p.CProductosId');
        $this->db->where('DATE(p.FechaQuemado)', $this->FechaIngles($dia));
        $this->db->where('cp.Activo', 1);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.HornosId', $horno);
        $this->db->where('p.CProductosId', $cprod);
        $this->db->where('p.Clasificado', 0);
        $query = $this->db->get()->num_rows();
        return $query;
    }

    public function ProductosPendientesModelos($dia, $horno, $cprod, $modelo) {
        $this->db->select('*');
        $this->db->from('Productos p');
        $this->db->where('DATE(p.FechaQuemado)', $this->FechaIngles($dia));
        $this->db->where('p.Activo', 1);
        $this->db->where('p.HornosId', $horno);
        $this->db->where('p.CProductosId', $cprod);
        $this->db->where('p.ModelosId', $modelo);
        $this->db->where('p.Clasificado', 0);
        $query = $this->db->get()->num_rows();
        return $query;
    }

    public function ImagenProductoModelo($cprod, $mod) {
        $this->db->select('*');
        $this->db->from('CProductosModelos cpm');
        $this->db->where('cpm.CProductosId', $cprod);
        $this->db->where('cpm.ModelosId', $mod);
        $query = $this->db->get()->row()->Imagen;
        return $query;
    }

    public function ListaModelos($dia, $horno, $cprod) {
        //print_r($dia . ' ' . $horno . ' ' . $cprod);
        $this->db->select('m.Nombre,p.ModelosId,p.CProductosId,p.HornosId');
        $this->db->from(' Productos p ');
        $this->db->join(' Modelos m', 'm.IdModelos=p.ModelosId ');
        $this->db->where(' DATE(p.FechaQuemado) ', $dia);
        $this->db->where(' p.CProductosId ', $cprod);
        $this->db->where(' p.Activo ', 1);
        $this->db->where(' p.HornosId ', $horno);
        $this->db->where(' p.Clasificado ', 0);
        $this->db->group_by(' m.IdModelos ');
        //print_r($this->db->get_compiled_select());
        $query = $this->db->get();
        return $query;
    }

    public function ListaColores($dia, $horno, $cprod, $mod) {
        $this->db->select('c.Nombre,c.Descripcion,p.ModelosId,p.CProductosId,p.HornosId,c.IdColores,p.HornosId');
        $this->db->from('Productos p');
        $this->db->join('Colores c', 'c.IdColores=p.ColoresId');
        $this->db->where('DATE(p.FechaQuemado)', $dia);
        $this->db->where('p.CProductosId', $cprod);
        $this->db->where('p.ModelosId', $mod);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.HornosId', $horno);
        $this->db->where('p.Clasificado', 0);
        $this->db->group_by('c.IdColores');
        $query = $this->db->get();
        return $query;
    }

    public function ListaTodosColores() {
        $this->db->select('c.Nombre,c.Descripcion,c.IdColores');
        $this->db->from('Colores c');
        $query = $this->db->get();
        return $query;
    }

    public function ProductosPendientesColores($dia, $horno, $cprod, $modelo, $color) {
        $this->db->select('*');
        $this->db->from('Productos p');
        $this->db->where('DATE(p.FechaQuemado)', $this->FechaIngles($dia));
        $this->db->where('p.Activo', 1);
        $this->db->where('p.HornosId', $horno);
        $this->db->where('p.Clasificado ', 0);
        $this->db->where('p.CProductosId', $cprod);
        $this->db->where('p.ModelosId', $modelo);
        $this->db->where('p.ColoresId', $color);
        $query = $this->db->get()->num_rows();
        return $query;
    }

    public function ProductosSeleccion($dia, $horno, $cprod, $mod, $color) {

        $this->db->select('c.Descripcion,cp.Nombre as NombreProducto,p.ModelosId,p.CProductosId,p.HornosId,c.IdColores,c.Nombre as NombreColor,m.Nombre as NombreModelo,p.IdProductos,p.FechaCaptura', FALSE);
        $this->db->from('Productos p');
        $this->db->join('Modelos m', "m.IdModelos=p.ModelosId");
        $this->db->join('CProductos cp', "cp.IdCProductos=p.CProductosId");
        $this->db->join('Colores c', "c.IdColores=p.ColoresId");
        $this->db->where('DATE(p.FechaQuemado)', $dia);
        $this->db->where('p.Activo', 1);
        $this->db->where('p.HornosId', $horno);
        $this->db->where('p.Clasificado ', 0);
        $this->db->where('p.CProductosId', $cprod);
        $this->db->where('p.ModelosId', $mod);
        $this->db->where('p.ColoresId', $color);
        $query = $this->db->get();
        return $query;
    }

    public function CategoriasDefectos() {
        $this->db->select('c.Nombre,c.IdCatDefectos');
        $this->db->from('CategoriasDefectos c');
        $this->db->where('c.Activo', 1);
        $query = $this->db->get();
        //print_r($this->db->get_compiled_select());
        return $query;
    }

    public function ListarDefectos($cat_id) {
        $this->db->select('d.Nombre,d.IdDefectos');
        $this->db->from('Defectos d');
        $this->db->where('d.Activo', 1);
        $this->db->where('d.CatDefectosId', $cat_id);
        $query = $this->db->get();
        return $query;
    }

    public function Clasificaciones() {
        $this->db->select('c.Letra,c.Color,c.IdClasificaciones');
        $this->db->from("Clasificaciones c");
        $this->db->where("Activo=", 1);
        return $this->db->get();
    }

    public function GuardarClasificacion($idprod, $idclasi, $fueratono) {
        $this->db->set("Clasificado", 1);
        $this->db->where("IdProductos", $idprod);
        $this->db->update("Productos");

        $datos = array(
            'ProductosId' => $idprod,
            'FechaClasificacion' => date('Y-m-d | h:i:sa'),
            'ClasificacionesId' => $idclasi,
            'FueraTono' => $fueratono,
            'UsuariosId' => 1
        );
        $this->db->insert('HistorialClasificacion', $datos);
        return $this->db->insert_id();
    }

    public function GuardarDefectos($defecto1, $puestodefecto1, $defecto2, $puestodefecto2, $idclasificacion) {
        if ($defecto1 != null && $defecto1 != 0) {
            $datos = array(
                'Defectosid' => $defecto1,
                'HistorialClasificacionId' => $idclasificacion,
                'PuestosId' => $puestodefecto1,
                'Activo' => 1
            );
            $this->db->insert("HistorialClasificacionDefectos", $datos);
        }
        if ($defecto2 != null && $defecto2 != 0) {
            $datos2 = array(
                'Defectosid' => $defecto2,
                'HistorialClasificacionId' => $idclasificacion,
                'PuestosId' => $puestodefecto2,
                'Activo' => 1
            );
            $this->db->insert("HistorialClasificacionDefectos", $datos2);
        }
    }

    public function ObtenerArea($categoria) {
        $this->db->select("a.Nombre");
        $this->db->from("CategoriasDefectos c");
        $this->db->join("Areas a", "a.IdAreas=c.AreasId");
        $this->db->where("c.IdCatDefectos", $categoria);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row()->Nombre;
        } else {
            return "";
        }
    }

    public function BuscarClavePuesto($clave, $categoria) {
        $area = $this->ObtenerArea($categoria);
        $this->db->select("p.Nombre,p.APaterno,p.AMaterno,pu.IdPuestos");
        $this->db->from("Puestos pu ");
        $this->db->join("Personas p", "p.IdPersonas=pu.PersonasId");
        $this->db->join("Areas a", "a.IdAreas=pu.AreasId");
        $this->db->where("a.Nombre", $area);
        $this->db->where("pu.Clave", $clave);
        //print($this->db->get_compiled_select());
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            //print("si entro");
            return $fila->row();
        } else {
            return "No se encontró trabajador";
        }
    }

    public function Producto($idprod) {
        $this->db->select("*");
        $this->db->from("Productos");
        $this->db->where("IdProductos", $idprod);
        return $this->db->get()->row();
    }

    public function GuardarAccesorio($colorseleccionado) {
        $datos = array(
            'CProductosId' => 7,
            'ColoresId' => $colorseleccionado,
            'UsuariosId' => 1,
            'Activo' => 1,
            'Clasificado' => 1,
            'ModelosId' => 1,
            'FechaCaptura' => date('Y-m-d | h:i:sa')
        );
        $this->db->insert("Productos", $datos);
        return $this->db->insert_id();
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

    public function BuscarClaveProductoDevoluciones($clave) {
        $this->db->select("p.IdProductos, cp.Nombre as producto, c.Nombre as color, m.Nombre as modelo");
        $this->db->from("DetalleDevoluciones dd");
        $this->db->join("Productos p", "p.IdProductos=dd.ProductosId");
        $this->db->join("CProductos cp", "p.CProductosId=cp.IdCProductos");
        $this->db->join("Colores c", "p.ColoresId=c.IdColores");
        $this->db->join("Modelos m", "p.ModelosId=m.IdModelos");
        $this->db->where("p.Activo", 1);
        $this->db->where("p.IdProductos", $clave);
        $fila = $this->db->get();
        if ($fila->num_rows() > 0) {
            return $fila->row();
        } else {
            return "No se encontró el producto en devoluciones";
        }
    }

    public function GuardarTarima() {
        $datos = array(
            'UsuarioCapturaId' => 1,
            'Activo' => 1,
            'FechaCaptura' => date('Y-m-d | h:i:sa')
        );
        $this->db->insert("Tarimas", $datos);
        return $this->db->insert_id();
    }

    public function GuardarDetalleTarima($idproducto, $idtarima) {
        if ($this->VerificarProductoTarima($idproducto) == "no existe") {
            $datos = array(
                'ProductosId' => $idproducto,
                'Activo' => 1,
                'TarimasId' => $idtarima
            );
            $this->db->insert("DetalleTarimas", $datos);
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

}

?>
