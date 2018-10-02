<script>
    function LiberarPedido(id)
    {
        $.post("LiberarPedido", {"id": id}, function (data) {
            $("#botonliberar" + id).fadeOut();
            $("#tdliberar" + id).html("<b class='fg-darkGreen'>Liberado</b>");

            var dt = new Date();

// Display the month, day, and year. getMonth() returns a 0-based number.
            var month = dt.getMonth() + 1;
            var day = dt.getDate();
            var year = dt.getFullYear();
            $("#tdfecha").html("<b class='fg-green'>" + year + '-' + month + '-' + day + "</b>");

            MsjCorrecto("El pedido se liberó correctamente");
        });
    }
    $(document).ready(function () {

    });
</script>
<h1 class="light text-shadow">PEDIDOS</h1><br>

<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
    <ul class="tabs">
        <li class="active"><a href="#pedidoscapturados" >Pedidos Solicitados</a></li>
        <li class="active"><a href="#pedidosliberados" >Pedidos Liberados</a></li>
        <li class="active"><a href="#pedidosentregados" >Pedidos Entregados</a></li>
    </ul>
    <div class="frames">
        <div class="frame" id="pedidoscapturados">
            <div class="panel warning" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkOrange"></span>
                    <span class="title">Lista de pedidos solicitados</span>
                </div>
                <div class="content" id="listapedidos" style="padding: 15px">
                    <table class="dataTable border bordered hovered hover" id="tablalistapedidos" data-role="datatable">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Fecha registro</th>
                                <th>Cliente</th>
                                <th>Nota</th>
                                <th>Resumen</th>
                                <th>Liberar</th>
                            </tr>
                        </thead>
                        <?php foreach ($ListaPedidosCapturados->result() as $pedido): ?>
                            <tr>
                                <td><?= $pedido->IdPedidos ?></td>
                                <td><?= $pedido->FechaRegistro ?></td>
                                <td><?= $pedido->Cliente ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modelocedis");
                                    $resumen = $ci->modelocedis->ResumenProductosPedidoAgrupados($pedido->IdPedidos);
                                    ?>
                                    <ul class="simple-list">
                                        <?php foreach ($resumen->result() as $r): ?>
                                            <li >
                                                <?= $r->Cantidad ?>
                                                <?= $r->producto ?>
                                                <?= $r->modelo ?>
                                                <?= $r->color ?>
                                                <?= $r->clasificacion ?>
                                                <br>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td class="center" id="tdliberar<?= $pedido->IdPedidos ?>">
                                    <?php
                                    if ($pedido->FechaLiberacion != null) {
                                        echo "<b class='fg-darkGreen'>Liberado</b>";
                                    } else {
                                        ?>
                                        <button id="botonliberar<?= $pedido->IdPedidos ?>" class="button block-shadow-success text-shadow success big-button" onclick="LiberarPedido(<?= $pedido->IdPedidos ?>)">Liberar</button>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="frame" id="pedidosliberados">
            <div class="panel warning" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkOrange"></span>
                    <span class="title">Lista de pedidos liberados</span>
                </div>
                <div class="content" id="listapedidos" style="padding: 15px">
                    <table class="dataTable border bordered hovered hover" id="tablalistapedidos" data-role="datatable">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Fecha registro</th>
                                <th>Fecha liberación</th>
                                <th>Cliente</th>
                                <th>Nota</th>
                                <th>Resumen</th>

                            </tr>
                        </thead>
                        <?php foreach ($ListaPedidosLiberados->result() as $pedido): ?>
                            <tr>
                                <td><?= $pedido->IdPedidos ?></td>
                                <td><?= $pedido->FechaRegistro ?></td>
                                <td class="center">
                                    <?php
                                    if ($pedido->FechaLiberacion != null) {
                                        echo "<b class='fg-green'>$pedido->FechaLiberacion</b>";
                                    } else {
                                        echo "<h6 class='fg-red'><i><b>Crédito y Cobranza no ha liberado el pedido</b></i></h6>";
                                    }
                                    ?>
                                </td>
                                <td><?= $pedido->Cliente ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modelocedis");
                                    $resumen = $ci->modelocedis->ResumenProductosPedidoAgrupados($pedido->IdPedidos);
                                    ?>
                                    <ul class="simple-list">
                                        <?php foreach ($resumen->result() as $r): ?>
                                            <li >
                                                <?= $r->Cantidad ?>
                                                <?= $r->producto ?>
                                                <?= $r->modelo ?>
                                                <?= $r->color ?>
                                                <?= $r->clasificacion ?>
                                                <br>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="frame" id="pedidosentregados">
            <div class="panel warning" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkOrange"></span>
                    <span class="title">Lista de pedidos entregados</span>
                </div>
                <div class="content" id="listapedidos" style="padding: 15px">
                    <table class="dataTable border bordered hovered hover" id="tablalistapedidos" data-role="datatable">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Fecha registro</th>
                                <th>Fecha liberación</th>
                                <th>Fecha salida</th>
                                <th>Cliente</th>
                                <th>Nota</th>
                                <th>Resumen</th>

                            </tr>
                        </thead>
                        <?php foreach ($ListaPedidosEntregados->result() as $pedido): ?>
                            <tr>
                                <td><?= $pedido->IdPedidos ?></td>
                                <td><?= $pedido->FechaRegistro ?></td>
                                <td class="center">
                                    <?php
                                    if ($pedido->FechaLiberacion != null) {
                                        echo "<b class='fg-green'>$pedido->FechaLiberacion</b>";
                                    } else {
                                        echo "<h6 class='fg-red'><i><b>Crédito y Cobranza no ha liberado el pedido</b></i></h6>";
                                    }
                                    ?>
                                </td>
                                <td><?= $pedido->FechaSalida ?></td>
                                <td><?= $pedido->Cliente ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modelocedis");
                                    $resumen = $ci->modelocedis->ResumenProductosPedidoAgrupados($pedido->IdPedidos);
                                    ?>
                                    <ul class="simple-list">
                                        <?php foreach ($resumen->result() as $r): ?>
                                            <li >
                                                <?= $r->Cantidad ?>
                                                <?= $r->producto ?>
                                                <?= $r->modelo ?>
                                                <?= $r->color ?>
                                                <?= $r->clasificacion ?>
                                                <br>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>

