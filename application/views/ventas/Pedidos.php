<script>
    $(document).ready(function () {
<?php
$correcto = $this->session->flashdata('correcto');
if ($correcto) {
    ?>
            MsjCorrecto("<?= $correcto ?>");
    <?php
}
?>
    });
</script>
<h1 class="light text-shadow">PEDIDOS</h1><br>

<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
    <ul class="tabs">
        <li class="active"><a href="#pedidoscapturados" >Pedidos Importados</a></li>
        <li class="active"><a href="#pedidosliberados" >Pedidos Liberados</a></li>
        <li class="active"><a href="#pedidosentregados" >Pedidos Entregados</a></li>
        <li class="active"><a href="#importar" >Importar Pedido</a></li>
    </ul>
    <div class="frames">
        <div class="frame" id="pedidoscapturados">
            <div class="panel warning" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkOrange"></span>
                    <span class="title">Lista de pedidos importados</span>
                </div>
                <div class="content" id="listapedidos" style="padding: 15px">
                    <table class="dataTable border bordered hovered hover" id="tablalistapedidos" data-role="datatable">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Fecha registro</th>
                                <th>Cliente</th>
                                <th>Nota CEDIS</th>
                                <th>Nota Crédito y Cobranza</th>
                                <th>Resumen</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <?php foreach ($ListaPedidosCapturados->result() as $pedido): ?>
                            <tr>
                                <td><?= $pedido->IdPedidos ?></td>
                                <td><?= $pedido->FechaRegistro ?>
                                    <?php
                                    $ci = &get_instance();
                                    $ci->load->model("modeloventas");
                                    $npen = $ci->modeloventas->Usuario($pedido->UsuariosId);?>
                                    <br><br>
                                    <?= $npen->Nombre." ".$npen->APaterno ?>
                                </td>
                                <td><?= $pedido->Cliente ?></td>
                                <td><?= $pedido->NotaCedis ?></td>
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
                                <td class="center">
                                    <div class="input-control text big-input medium-size">
                                        <a class="button primary large-button text-shadow block-shadow-primary" href="AbrirPedido?pedidoid=<?= $pedido->IdPedidos ?>">Editar</a>
                                    </div>
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
                                <th>Nota CEDIS</th>
                                <th>Nota Crédito y Cobranza</th>
                                <th>Resumen</th>
                                <th>Acción</th>
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
                                <td><?= $pedido->NotaCedis ?></td>
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
                                <td class="center">
                                    <div class="input-control text big-input medium-size">
                                        <a class="button primary large-button text-shadow block-shadow-primary" href="AbrirPedido?pedidoid=<?= $pedido->IdPedidos ?>">Editar</a>
                                    </div>
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
                                <th>Nota CEDIS</th>
                                <th>Nota Crédito y Cobranza</th>
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
                                <td><?= $pedido->NotaCedis ?></td>
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
        <div class="frame" id="importar">
            <div class="panel collapsible" data-role="panel">
                <div class="heading fg-white bg-Cyan">
                    <span class="icon mif-cog fg-white bg-darkCyan "></span>
                    <span class="title fg-white">Selecciona el archivo a importar</span>
                </div>
                <div class="content" style="text-align: center;">
                    <br><br>
                    <form action="ImportarPedido" method="POST" enctype="multipart/form-data">
                        <div class="grid">
                            <div class="row cells2">
                                <div class="cell">
                                    <h4>Formato aceptado .XLSX</h4>
                                    <div class="input-control file full-size" data-role="input">
                                        <input type="file" name="file" id="file" accept=".xlsx" class="form-control">
                                        <button class="button"><span class="mif-folder"></span></button>
                                    </div>
                                </div>
                                <div class="cell">
                                    <h4>Cliente</h4>
                                    <div class="input-control full-size text">
                                        <input type="text" placeholder="Teclea el nombre del cliente" name="cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="row cells2">
                                <div class="cell">
                                    <h4>Nota para crédito y cobranza</h4>
                                    <div class="input-control full-size text">
                                        <textarea type="text" name="notacredito"></textarea>
                                    </div>
                                </div>
                                <div class="cell">
                                    <h4>Nota para CEDIS</h4>
                                    <div class="input-control full-size text">
                                        <textarea type="text" name="notacedis"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row cells2">
                                <div class="cell colspan2">
                                    <br><br><br><br>
                                    <button class="button block-shadow-success text-shadow success big-button">Importar Pedido</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>

