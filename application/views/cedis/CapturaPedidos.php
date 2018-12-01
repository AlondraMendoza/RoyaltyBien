<script>
    var guardado = 0;
    function VerificarClave(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProd").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des").html("Verificando clave...");
                $.getJSON("VerificarProductoCedis", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#des").html("Producto encontrado");
                        if ($("#td" + data.id).length)
                        {
                            $.Notify({
                                caption: 'Error',
                                content: 'Ya agregaste el producto a la lista',
                                type: 'alert'
                            });
                            $("#claveProd").val("");
                            $("#des").html("");
                        } else {
                            //metodo para Abrir tabla y agregar datos
                            var input = '<tr><td class="center">' + data.id + '</td><td class="center">';
                            input += '<b style="font-size: 1.3em" class="fg-darkEmerald">Descripción:</b><br>';
                            input += data.nombre;
                            input += '</td>';
                            input += '<td class="center" id="td' + data.id + '"><label class="input-control checkbox">';
                            input += '<input type="checkbox" name="IDS[]" value="' + data.id + '" checked>';
                            input += '<span class="check"></span>';
                            input += '</label></td></tr>';
                            $("#tablaproductos").append(input);
                            $("#claveProd").val("");
                            $("#des").html("");
                            $("#resultadosproductos").fadeIn();
                        }
                    } else
                    {
                        $("#des").html(data.nombre);
                    }
                });
            }
        }
    }
    function GuardarPedido() {
        /*
         *
         * if ( $("#undiv").length ) {
         */
        if (guardado == 0) {
            var cliente = $("#cliente").val();
            if (cliente == "")
            {
                $.Notify({
                    caption: 'Error',
                    content: 'Teclea el nombre del cliente',
                    type: 'alert'
                });
                return 0;
            }
            $.post("GuardarPedidoCedis", {"cliente": cliente}, function (idpedido) {
                $("input[name='IDS[]']:checked").each(function () {
                    var id = $(this).val();
                    $.post("GuardarDetallePedidoCedis", {"idproducto": $(this).val(), "idpedido": idpedido}, function (data) {
                        if (data == "Correcto")
                        {
                            $("#td" + id).html('<span class="mif-checkmark fg-green"></span> Producto Guardado');
                        } else if (data == "En pedido")
                        {
                            $("#td" + id).html("<span class='mif-cancel fg-red'></span> El producto ya se encuentra <br>en un pedido");
                        } else
                        {
                            $.Notify({
                                caption: 'Error',
                                content: 'Ocurrió un error al guardar el producto',
                                type: 'alert'
                            });
                        }
                    });
                });
            });
            guardado = 1;
            $("#botonguardar").fadeOut();
            $("#nuevatarima").fadeIn();
        }
        //$("#tablaproductos").empty();
    }
    function AbrirPedido(pedidoid)
    {
        $("#pedidos").load("AbrirPedido", {"pedidoid": pedidoid});
    }
    function Cancelar() {
        location.reload(true);
    }
</script>
<h1><b> PEDIDOS</b></h1><br>
<center>
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
                        <table class="dataTable border bordered hovered hover " id="tablalistapedidos" data-role="datatable">
                            <thead>
                                <tr class="row">
                                    <th class="cell">Clave</th>
                                    <th class="cell">Fecha registro</th>
                                    <th class="cell">Cliente</th>
                                    <th class="cell">Nota</th>
                                    <th>Resumen</th>
                                </tr>
                            </thead>
                            <?php foreach ($ListaPedidosCapturados->result() as $pedido): ?>
                                <tr class="row">
                                    <td class="cell"><?= $pedido->IdPedidos ?></td>
                                    <td class="cell"><?= $pedido->FechaRegistro ?>
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
                                    <td style="width:200px"><?= $cliente->Nombre ?></td>
                                    <td class="cell"><?= $pedido->NotaCedis ?></td>
                                    <td class="cell">
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
                                    <th>Acción</th>
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
                                    <td style="width:200px"><?= $cliente->Nombre ?></td>
                                    <td><?= $pedido->NotaCedis ?></td>
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
                                        <div class="input-control text big-input medium-size">
                                            <a class="button primary large-button text-shadow block-shadow-primary" href="AbrirPedido?pedidoid=<?= $pedido->IdPedidos ?>">Abrir Pedido</a>
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
                                    <td style="width:200px"><?= $cliente->Nombre ?></td>
                                    <td><?= $pedido->NotaCedis ?></td>
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
</center><br><br><br><br><br><br>


