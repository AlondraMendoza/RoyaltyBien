<div class="panel  fg-white" data-role="panel">
    <div class="heading bg-teal">
        <span class="icon mif-stack fg-white bg-darkTeal"></span>
        <span class="title">Información general de pedido</span>
    </div>
    <div class="content" id="Inicio">
        <table class = " table dataTable border bordered hovered hover" id = "tablalistaproductos" data-role = "datatable">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Verificación</th>
                </tr>
            </thead>
            <?php
            $completo = true;
            $completosub = true;
            
            ?>
            <?php foreach ($ListaProductosAgrupados->result() as $pa): ?>
                <?php
                $bgcolor = "grayLight";
                $ci = &get_instance();
                $ci2 = &get_instance();
                $ci->load->model("modelocedis");
                $ci2->load->model("modeloalmacenista");
                $cuantos = $ci->modelocedis->ObtenerProductosPedido($pedido->IdPedidos, $pa->IdPedidosVentas);
                $clave = $ci->modelocedis->ClaveProducto($pa->CProductosId, $pa->ModelosId,$pa->ColoresId,$pa->ClasificacionesId);
                if ($cuantos >= $pa->Cantidad) {
                    $bgcolor = "lightOlive";
                } else {
                    $completo = false;
                }
                ?>
                <tr class="bg-<?= $bgcolor ?> fg-white">
                    <td><?= $clave ?></td>
                    <td><?= $pa->Descripcion ?></td>
                    <td><?= $pa->Cantidad ?></td>
                    <td>
                        <span id="cuantos<?= $pa->IdPedidosVentas ?>"><?= $cuantos ?></span> de <span id="decuantos<?= $pa->IdPedidosVentas ?>"><?= $pa->Cantidad ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php
            $resumen2 = $ci->modelocedis->ResumenSubProductosPedidoAgrupados($pedido->IdPedidos);
            
            ?>
            <?php foreach ($resumen2->result() as $prod):
                $cuantosub = $ci2->modeloalmacenista->ExistenciasSubproductos($prod->CGriferiaId);
                $bgcolors = "grayLight";
                if ($cuantosub >= $prod->Cantidad) {
                    $bgcolors = "lightOlive";
                } else {
                    $completosub = false;
                }
            ?>
                <tr class="bg-<?= $bgcolors ?> fg-white">
                    <td><?= $prod->Clave ?></td>
                    <td><?= $prod->producto ?></td>
                    <td><?= $prod->Cantidad ?></td>
                    <td><?= $cuantosub ?> de <?= $prod->Cantidad ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php
if ($completo  ) 
{
    if($pedido->Estatus == "Liberado")
    {
        if($completosub)
        {
            ?>
            <script>
                $("#divbotonsalida").html('<button class="button danger" id="botonsalida" onclick="MarcarSalidaProducto()">Salida de Pedido</button>');
            </script>
            <?php
        }
        else
        {
            ?>
            <script>
                $("#divbotonsalida").html('<small class="fg-red">No se cuenta con la cantidad necesaria de subproductos en almacén para surtir pedido</small>');
            </script>
            <?php
        }
    }
    else if($pedido->Estatus=="Entregado")
    {
        ?>
        <script>
            $("#divbotonsalida").html('<small class="fg-red">Ya se dió salida al pedido.</small>');
        </script>
        <?php
    }
    else
    {
        ?>
        <script>
            $("#divbotonsalida").html('<small class="fg-red">No es posible dar salida a pedido ya que no está liberado por crédito y cobranza</small>');
        </script>
        <?php
    }
}
else
{
    ?>
    <script>
        $("#divbotonsalida").html('<small class="fg-red">No es posible dar salida a pedido ya que no está completo</small>');
    </script>
    <?php
}
?>

