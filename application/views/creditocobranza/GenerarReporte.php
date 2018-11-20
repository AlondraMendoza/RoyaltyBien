<script>
    function RegresoCriterios()
    {
        $("#divtiporeporte").show();
        $("#detalle").hide();
        $("#grafica").html("");
        $("#detalleseleccionado").html("");
    }
</script>
<center><button onclick="RegresoCriterios()" class="button block-shadow-info text-shadow primary big-button">Regresar a Criterios de Selección</button></center>
<br>
<table class="table shadow" data-role="datatable">
    <thead>
        <tr>
            <th colspan="9" class="fg-darkBlue">RESULTADOS</th>
        </tr>
        <tr>
            <th>No.</th>
            <th>Producto</th>
            <th>Modelo</th>
            <th>Color</th>
            <th>Clasificación</th>
            <th>Inventario Real</th>
            <th>Inventario Virtual</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        $cont = 1;
        ?>
        <?php foreach ($productos->result() as $producto): ?>
            <?php
            $ci = &get_instance();
            $ci->load->model("modeloadministrador");
            $clasificacion = $ci->modeloadministrador->Clasificacion($producto->IdProductos);
            $letra = "Sin Clasificar";
            if ($clasificacion != "") {
                $letra = $clasificacion->Letra;
            }
            ?>
            <tr>
                <td class="center"><?= $cont ?></td>
                <td class="center"><?= $producto->producto ?></td>
                <td class="center"><?= $producto->modelo ?></td>
                <td class="center"><?= $producto->color ?></td>
                <td class="center"><?= $letra ?></td>
                <td class="center"><?php $producto->cuantos;
                $ci = &get_instance();
                $ci->load->model("modeloventas");
                $ped = $ci->modeloventas->EnPedido($producto->IdProductos);
                $ci2 =&get_instance();
                $ci2->load->model("modelocedis");
                $pedi = $ci->modelocedis->ProductosPedidosVentas($ped->ModelosId, $ped->ColoresId, $ped->ClasificacionesId, $ped->CProductosId);
                $ci3 =&get_instance();
                $ci3->load->model("modeloalmacenista");
                $almacen = $ci3->modeloalmacenista->ProductosEnAlmacenTotal($ped->ModelosId, $ped->ColoresId, $ped->ClasificacionesId, $ped->CProductosId);
                $SubTotal= $producto->cuantos+$almacen;
                if ($SubTotal<0){?>
                    <b class="fg-red"><?= $SubTotal; ?> </b><?php
                }else{?>
                    <b class="fg-darkBlue"> <?= $SubTotal; ?> </b><?php
                }
                ?>
                </td>
                <td class="center">
                <?php $Total= $producto->cuantos+$almacen-$pedi;
                if ($Total<0){?>
                    <b class="fg-red"><?= $Total; ?> </b><?php
                }else{?>
                    <b class="fg-darkGreen"> <?= $Total; ?> </b><?php
                }
                ?>
                </td>
                
            </tr>
            <?php $cont++; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<br><br>
<center><button onclick="RegresoCriterios()" class="button block-shadow-info text-shadow primary big-button">Regresar a Criterios de Selección</button></center>