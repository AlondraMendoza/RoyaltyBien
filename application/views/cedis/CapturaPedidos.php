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
<h1><b> CAPTURA DE PEDIDOS</b></h1><br>
<center>
    <div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
        <ul class="tabs">
            <li class="active"><a href="#productos">Captura de pedidos</a></li>
            <li><a href="#pedidos">Pedidos capturados
                </a>
            </li>
        </ul>
        <div class="frames">
            <div class="frame" id="pedidos">
                <div class="panel warning" data-role="panel">
                    <div class="heading">
                        <span class="icon mif-stack fg-white bg-darkOrange"></span>
                        <span class="title">Lista de pedidos pendientes</span>
                    </div>
                    <div class="content" id="listapedidos" style="padding: 15px">
                        <table class="dataTable border bordered hovered hover" id="tablalistapedidos" data-role="datatable">
                            <thead>
                                <tr>
                                    <th>Clave</th>
                                    <th>Fecha registro</th>
                                    <th>Cliente</th>
                                    <th>Resumen</th>
                                    <th style="width: 15%">Acción</th>
                                </tr>
                            </thead>
                            <?php foreach ($ListaPedidos->result() as $pedido): ?>
                                <tr>
                                    <td><?= $pedido->IdPedidos ?></td>
                                    <td><?= $pedido->FechaRegistro ?></td>
                                    <td><?= $pedido->Cliente ?></td>
                                    <td>
                                        <?php
                                        $ci = &get_instance();
                                        $ci->load->model("modelocedis");
                                        $resumen = $ci->modelocedis->ResumenProductosPedido($pedido->IdPedidos);
                                        ?>
                                        <?php foreach ($resumen->result() as $r): ?>
                                            <?= $r->cantidad ?>
                                            <?= $r->producto ?>
                                            <?= $r->modelo ?>
                                            <br>
                                        <?php endforeach; ?>

                                    </td>
                                    <td class="center">
                                        <div class="input-control text big-input medium-size">
                                            <a class="button warning large-button text-shadow block-shadow-warning" href="AbrirPedido?pedidoid=<?= $pedido->IdPedidos ?>">Abrir Pedido</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <br><br><br>
                    <br><br><br>
                </div>
                <br><br><br>
                <br><br><br>
            </div>
            <div class="frame" id="productos">
                <div class="panel warning" data-role="panel">
                    <div class="heading">
                        <span class="icon mif-stack fg-white bg-darkOrange"></span>
                        <span class="title">Ingresar Código de Barras de producto</span>
                    </div>
                    <div class="content" id="Inicio">
                        <table class="table">
                            <tr>
                                <td class="center">
                                    <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                                    <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                        <input type="text" id="claveProd" onkeyup="VerificarClave(event)">
                                    </div>
                                    <br><label><span id="des"></span></label>
                                </td>
                            </tr>
                            <br><br>
                        </table>
                    </div>
                </div>
                <div id="resultadosproductos" style="display: none">
                    <center>
                        <div class="panel primary" data-role="panel">
                            <div class="heading">
                                <span class="icon mif-stack fg-white bg-darkBlue"></span>
                                <span class="title">Detalle de Productos agregados</span>
                            </div>
                            <br>
                            <table class="table">
                                <tr>
                                    <td class="center">
                                        <b style="font-size: 1.2em" class="fg-darkEmerald"> Nombre completo de cliente:</b><br>
                                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                            <input type="text" id="cliente" onkeyup="" placeholder="Teclea el nombre del cliente">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="content" id="Resultados">
                                <table class="table bordered border hovered" id="tablaproductos">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Descripción</th>
                                            <th>Seleccion/Acción</th>
                                        </tr>
                                    </thead>
                                </table>
                                <table>
                                    <tr>
                                        <td class="center" id="Botones"><br>
                                            <div class="input-control text big-input medium-size" id="nuevatarima" style="display: none">
                                                <button class="button warning" onclick="Cancelar()">Nuevo Pedido</button></div>
                                            <div class="input-control text big-input medium-size" id="botonguardar">
                                                <button class="button success" onclick="GuardarPedido()">Guardar</button></div>
                                            <div class="input-control text big-input medium-size">
                                                <button class="button danger" onclick="Cancelar()">Cancelar</button></div>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>
</center><br><br><br>


