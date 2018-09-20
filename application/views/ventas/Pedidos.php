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
        <li class="active"><a href="#pedidos" >Pedidos</a></li>
        <li class="active"><a href="#importar" >Importar Pedido</a></li>
    </ul>
    <div class="frames">
        <div class="frame" id="pedidos">
            <div class="panel warning" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkOrange"></span>
                    <span class="title">Lista de pedidos capturados</span>
                </div>
                <div class="content" id="listapedidos" style="padding: 15px">
                    <table class="dataTable border bordered hovered hover" id="tablalistapedidos" data-role="datatable">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Fecha registro</th>
                                <th>Fecha liberación</th>
                                <th>Cliente</th>
                                <th>Resumen</th>

                            </tr>
                        </thead>
                        <?php foreach ($ListaPedidos->result() as $pedido): ?>
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
                            <div class="row cells3">
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
                                <div class="cell">
                                    <br>
                                    <button class="button block-shadow-success text-shadow success big-button">Importar Pedido</button>
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

