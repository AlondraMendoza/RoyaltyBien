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
function ConsultarCliente(texto)
{
    if(texto.length>2)
    {
        $("#divcliente").load("ConsultarCliente",{"texto":texto});
    }
}
function CambioContabilizar(pedido)
{
    $.get("CambioContabilizar",{"pedido_id":pedido},function(data){
        if(data.includes("correcto"))
        {
            MsjCorrecto("El pedido se actualizó correctamente.");
        }
    });
}

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
                                <th>Fecha posible entrega</th>
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
                                        $cliente = $ci->modeloventas->Cliente($pedido->ClientesId);
                                    }
                                    ?>
                                    <br><br><div class="text-small fg-darkGreen">Creó:<br>
                                        <?= $npen->Nombre . " " . $npen->APaterno ?>
                                        
                                    </div>
                                    <div class="text-small fg-darkGreen">
                                        <?= $textomodifico ?>
                                    </div>
                                </td>
                                <td class="center"><?= $pedido->FechaPosibleEntrega ?></td>
                                <td><?= $cliente->Nombre ?></td>
                                <td><?= $pedido->NotaCedis ?></td>
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
                                <td class="center">
                                    <form action="ReimportarPedido" method="POST" enctype="multipart/form-data">
                                        <div class="input-control file full-size" data-role="input">
                                            <input type="file" name="file" id="file" accept=".xlsx" class="form-control">
                                            <button class="button"><span class="mif-folder"></span></button>
                                        </div>
                                        <input type="hidden" name="pedido_id" value="<?= $pedido->IdPedidos ?>">
                                        <br>
                                        <div class="input-control text big-input medium-size">
                                            <button type="submit" class="button primary large-button text-shadow block-shadow-primary">Reimportar</button>
                                        </div>
                                    </form>
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
                                <th>Fecha posible entrega</th>
                                <th>Fecha liberación</th>
                                <th>Cliente</th>
                                <th>Nota para CEDIS</th>
                                <th>Nota para C y C</th>
                                <th>Observación de liberación por C y C</th>
                                <th>Resumen</th>
                                <th>Contabilizar</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <?php foreach ($ListaPedidosLiberados->result() as $pedido): ?>
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
                                <td class="center"><?= $pedido->FechaPosibleEntrega ?></td>
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
                                <td><?= $pedido->NotaCedis ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td><?= $pedido->ObservacionLiberacion ?></td>
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
                                <td>
                                    <label class="input-control checkbox">
                                        <input type="checkbox" id="contabilizar<?= $pedido->IdPedidos?>" onchange="CambioContabilizar(<?= $pedido->IdPedidos?>)" <?= $pedido->CheckContabilizar? 'checked':'' ?>>
                                        <span class="check"></span>
                                        <span class="caption"></span>
                                    </label>
                                </td>
                                <td class="center">
                                    <form action="ReimportarPedido" method="POST" enctype="multipart/form-data">
                                        <div class="input-control file full-size" data-role="input">
                                            <input type="file" name="file" id="file" accept=".xlsx" class="form-control">
                                            <button class="button"><span class="mif-folder"></span></button>
                                        </div>
                                        <input type="hidden" name="pedido_id" value="<?= $pedido->IdPedidos ?>">
                                        <br>
                                        <div class="input-control text big-input medium-size">
                                            <button type="submit" class="button primary large-button text-shadow block-shadow-primary">Reimportar</button>
                                        </div>
                                    </form>
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
                                <th>Fecha posible entrega</th>
                                <th>Fecha liberación</th>
                                <th>Fecha salida</th>
                                <th>Cliente</th>
                                <th>Nota para CEDIS</th>
                                <th>Nota para C y C</th>
                                <th>Observación de Entrega por CEDIS</th>
                                <th>Resumen</th>
                            </tr>
                        </thead>
                        <?php foreach ($ListaPedidosEntregados->result() as $pedido): ?>
                            <tr>
                                <td><?= $pedido->IdPedidos ?></td>
                                <td class="center">
                                    <?= $pedido->FechaRegistro ?>
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
                                <td class="center"><?= $pedido->FechaPosibleEntrega ?></td>
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
                                <td class="center">
                                    <?= $pedido->FechaSalida ?>
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
                                <td><?= $pedido->NotaCedis ?></td>
                                <td><?= $pedido->NotaCredito ?></td>
                                <td><?= $pedido->ObservacionSalida ?></td>
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
                        <div class="row cells1">
                                <div class="cell">
                                    <h4>Cliente</h4>
                                    <div class="input-control full-size text">
                                        <input type="text" placeholder="Teclea el nombre del cliente" name="" onkeyup="ConsultarCliente(this.value)" id="textocliente">
                                    </div>
                                    <input type="hidden" name="cliente" id="inputcliente">
                                    <div id="divcliente" style="padding:10px"></div>
                                </div>
                            </div>
                            <div class="row cells2">
                                <div class="cell">
                                    <h4>Formato aceptado .XLSX</h4>
                                    <div class="input-control file full-size" data-role="input">
                                        <input type="file" name="file" id="file" accept=".xlsx" class="form-control">
                                        <button class="button"><span class="mif-folder"></span></button>
                                    </div>
                                </div>
                                <div class="cell">
                                    <h4>Fecha Posible Entrega</h4>
                                    <div class="input-control text full-size" data-role="datepicker" data-locale="es" data-format="dd/mm/yyyy" id="datepicker">
                                        <input type="text" id="fechaentrega" value="<?= $hoy ?>" name="fechaentrega">
                                        <button class="button"><span class="mif-calendar"></span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row cells2">
                                <div class="cell">
                                    <h4>Serie</h4>
                                    <div class="input-control full-size text">
                                        <input type="text" name="serie" id="">
                                    </div>
                                </div>
                                <div class="cell">
                                    <h4>Folio</h4>
                                    <div class="input-control full-size text">
                                        <input type="text" name="folio" id="">
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

