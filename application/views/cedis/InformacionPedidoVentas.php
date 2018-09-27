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
            ?>
            <?php foreach ($ListaProductosAgrupados->result() as $pa): ?>
                <?php
                $bgcolor = "grayLight";
                $ci = &get_instance();
                $ci->load->model("modelocedis");
                $cuantos = $ci->modelocedis->ObtenerProductosPedido($pedido->IdPedidos, $pa->IdPedidosVentas);
                if ($cuantos >= $pa->Cantidad) {
                    $bgcolor = "lightOlive";
                } else {
                    $completo = false;
                }
                ?>
                <tr class="bg-<?= $bgcolor ?> fg-white">
                    <td><?= $pa->IdPedidosVentas ?></td>
                    <td><?= $pa->Descripcion ?></td>
                    <td><?= $pa->Cantidad ?></td>
                    <td>
                        <span id="cuantos<?= $pa->IdPedidosVentas ?>"><?= $cuantos ?></span> de <span id="decuantos<?= $pa->IdPedidosVentas ?>"><?= $pa->Cantidad ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php
if ($completo && $pedido->Estatus == "Liberado") {
    ?>
    <script>
        $("#divbotonsalida").html('<button class="button danger" id="botonsalida" onclick="MarcarSalidaProducto()">Salida de Pedido</button>');
    </script>
    <?php
}
?>