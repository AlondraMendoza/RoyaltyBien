<script>
    function LiberarPedido(id)
    {
        var observacion = $("#observacioncredito").val();
        $.post("LiberarPedido", {"id": id, "observacionliberacion": observacion}, function (data) {
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
                                <td><?= $pedido->FechaRegistro ?>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modeloventas");
                                    $npen = $ci->modeloventas->Usuario($pedido->UsuariosId);
                                    $cliente = $ci->modeloventas->Cliente($pedido->ClientesId);
                                    $textomodifico = "";
                                    if ($pedido->UsuarioModificaId != null) {
                                        $modifico = $ci->modeloventas->Usuario($pedido->UsuarioModificaId);
                                        $textomodifico = "<br><br>Modificó: " . $modifico->Nombre . " " . $modifico->APaterno;
                                    }
                                    ?>
                                    <br><br><div class="text-small fg-darkGreen">Creó:<br>
                                        <?= $npen->Nombre . " " . $npen->APaterno ?>
                                    </div>
                                    <div class="text-small fg-darkGreen">
                                        <?= $textomodifico ?>
                                    </div>
                                </td>
                                <td><?= $cliente->Nombre ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modelocedis");
                                    $resumen = $ci->modelocedis->ResumenProductosPedidoAgrupados($pedido->IdPedidos);
                                    $resumen2 = $ci->modelocedis->ResumenSubProductosPedidoAgrupados($pedido->IdPedidos);
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
                                        <?php 
                                        if($resumen2->num_rows()>0){
                                            echo"<hr>";
                                        }
                                        ?>
                                        <?php foreach ($resumen2->result() as $r2): ?>
                                     
                                            <li >
                                                <?= $r2->Cantidad ?>
                                                <?= $r2->producto ?>
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
                                        <b>Observación</b><br>
                                        <div class="input-control text">
                                            <input type="text" value="" id="observacioncredito">
                                        </div>
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
                                <td><?= $pedido->FechaRegistro ?>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modeloventas");
                                    $npen = $ci->modeloventas->Usuario($pedido->UsuariosId);
                                    $cliente = $ci->modeloventas->Cliente($pedido->ClientesId);
                                    $textomodifico = "";
                                    if ($pedido->UsuarioModificaId != null) {
                                        $modifico = $ci->modeloventas->Usuario($pedido->UsuarioModificaId);
                                        $textomodifico = "<br><br>Modificó: " . $modifico->Nombre . " " . $modifico->APaterno;
                                    }
                                    ?>
                                    <br><br><div class="text-small fg-darkGreen">Creó:<br>
                                        <?= $npen->Nombre . " " . $npen->APaterno ?>
                                    </div>
                                    <div class="text-small fg-darkGreen">
                                        <?= $textomodifico ?>
                                    </div>
                                </td>
                                <td class="center">
                                    <?php
                                    if ($pedido->FechaLiberacion != null) {
                                        echo "<b class='fg-green'>$pedido->FechaLiberacion</b>";
                                        $ci = &get_instance();
                                        $ci->load->model("modeloventas");
                                        $usuariolibero = $ci->modeloventas->Usuario($pedido->UsuarioLiberaId);
                                        ?>
                                        <br><br><div class="text-small fg-darkGreen">Liberó:<br>
                                            <?= $usuariolibero->Nombre . " " . $usuariolibero->APaterno ?>
                                        </div>
                                        <?php
                                    } else {
                                        echo "<h6 class='fg-red'><i><b>Crédito y Cobranza no ha liberado el pedido</b></i></h6>";
                                    }
                                    ?>
                                </td>
                                <td><?= $cliente->Nombre ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modelocedis");
                                    $resumen = $ci->modelocedis->ResumenProductosPedidoAgrupados($pedido->IdPedidos);
                                    $resumen2 = $ci->modelocedis->ResumenSubProductosPedidoAgrupados($pedido->IdPedidos);
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
                                        <?php 
                                        if($resumen2->num_rows()>0){
                                            echo"<hr>";
                                        }
                                        ?>
                                        <?php foreach ($resumen2->result() as $r2): ?>
                                     
                                            <li >
                                                <?= $r2->Cantidad ?>
                                                <?= $r2->producto ?>
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
                                <td class="center"><?= $pedido->FechaRegistro ?>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modeloventas");
                                    $npen = $ci->modeloventas->Usuario($pedido->UsuariosId);
                                    $cliente = $ci->modeloventas->Cliente($pedido->ClientesId);
                                    $textomodifico = "";
                                    if ($pedido->UsuarioModificaId != null) {
                                        $modifico = $ci->modeloventas->Usuario($pedido->UsuarioModificaId);
                                        $textomodifico = "<br><br>Modificó: " . $modifico->Nombre . " " . $modifico->APaterno;
                                    }
                                    ?>
                                    <br><br><div class="text-small fg-darkGreen">Creó:<br>
                                        <?= $npen->Nombre . " " . $npen->APaterno ?>
                                    </div>
                                    <div class="text-small fg-darkGreen">
                                        <?= $textomodifico ?>
                                    </div>
                                </td>
                                <td class="center">
                                    <?php
                                    if ($pedido->FechaLiberacion != null) {
                                        echo "<b class='fg-green'>$pedido->FechaLiberacion</b>";
                                        $ci = &get_instance();
                                        $ci->load->model("modeloventas");
                                        $usuariolibero = $ci->modeloventas->Usuario($pedido->UsuarioLiberaId);
                                        ?>
                                        <br><br><div class="text-small fg-darkGreen">Liberó:<br>
                                            <?= $usuariolibero->Nombre . " " . $usuariolibero->APaterno ?>
                                        </div>
                                        <?php
                                    } else {
                                        echo "<h6 class='fg-red'><i><b>Crédito y Cobranza no ha liberado el pedido</b></i></h6>";
                                    }
                                    ?>
                                </td>
                                <td class="center"><?= $pedido->FechaSalida ?>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modeloventas");
                                    $usuarioentrego = $ci->modeloventas->Usuario($pedido->UsuarioEntregaId);
                                    ?>
                                    <br><br><div class="text-small fg-darkGreen">Entregó:<br>
                                        <?= $usuarioentrego->Nombre . " " . $usuarioentrego->APaterno ?>
                                    </div>
                                </td>
                                <td><?= $cliente->Nombre ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modelocedis");
                                    $resumen = $ci->modelocedis->ResumenProductosPedidoAgrupados($pedido->IdPedidos);
                                    $resumen2 = $ci->modelocedis->ResumenSubProductosPedidoAgrupados($pedido->IdPedidos);
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
                                        <?php 
                                        if($resumen2->num_rows()>0){
                                            echo"<hr>";
                                        }
                                        ?>
                                        <?php foreach ($resumen2->result() as $r2): ?>
                                     
                                            <li >
                                                <?= $r2->Cantidad ?>
                                                <?= $r2->producto ?>
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

