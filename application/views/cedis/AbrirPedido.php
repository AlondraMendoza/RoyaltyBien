<script>
    var guardado = 0;
    function VerificarClavep(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProdp").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#desp").html("Verificando clave...");
                $.getJSON("VerificarProductoCedis", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#desp").html("Producto encontrado");
                        //metodo para Abrir tabla y agregar datos
                        GuardarPedido2(data.id);
                    } else
                    {
                        $("#desp").html(data.nombre);
                    }
                });
            }
        }
    }
    function GuardarPedido2(id) {
        /*
         *
         * if ( $("#undiv").length ) {
         */
        if (guardado == 0) {
            $.post("GuardarDetallePedidoCedis", {"idproducto": id, "idpedido": "<?= $pedidoid ?>"}, function (data) {
                if (data == "Correcto")
                {
                    $.Notify({
                        caption: 'Correcto',
                        content: 'El producto se agregó al pedido correctamente',
                        type: 'success'
                    });
                    $("#desp").html("El producto se agregó correctamente al pedido");
                    AbrirPedido2(<?= $pedidoid ?>)
                } else if (data == "En pedido")
                {
                    $.Notify({
                        caption: 'Error',
                        content: 'El producto ya se encuentra en un pedido',
                        type: 'alert'
                    });
                } else
                {
                    $.Notify({
                        caption: 'Error',
                        content: 'Ocurrió un error al guardar el producto',
                        type: 'alert'
                    });
                }
            });

            guardado = 1;
        }
        //$("#tablaproductos").empty();
    }
    function AbrirPedido2(pedidoid)
    {
        $("#pedidos").load("AbrirPedido", {"pedidoid": pedidoid});
    }
    function Cancelar() {
        location.reload(true);
    }
    function MarcarSalidaProducto()
    {
        $.post("SalidaCedis", {"pedidoid": "<?= $pedidoid ?>"}, function (data) {
            if (data == "correcto")
            {
                $.Notify({
                    caption: 'Correcto',
                    content: 'Se guardó la salida del pedido.',
                    type: 'success'
                });
                location.reload(true);
            } else
            {
                $.Notify({
                    caption: 'Error',
                    content: 'Ocurrió un error al marcar la salida del pedido.',
                    type: 'alert'
                });
            }
        });
    }
</script>
<h1><b> INFORMACIÓN DE PEDIDO: <?= $pedidoid ?></b></h1><br>
<div class="panel  fg-white" data-role="panel">
    <div class="heading bg-green">
        <span class="icon mif-stack fg-white bg-darkGreen"></span>
        <span class="title">Ingresar Código de Barras del Producto que Desea Agregar al Pedido</span>
    </div>
    <div class="content" id="Inicio">
        <table class="table">
            <tr>
                <td class="center">
                    <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                    <div class="input-control text full-size" style="height:80px;font-size: x-large">
                        <input type="text" id="claveProdp" onkeyup="VerificarClavep(event)">
                    </div>
                    <br><label><span id="desp"></span></label>
                </td>
            </tr>
            <br><br>
        </table>
    </div>
</div>
<div class="panel warning" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Lista de productos en pedido</span>
    </div>
    <div class="content" id="" style="padding: 15px">

        <br>
        <table class = " table dataTable border bordered hovered hover" id = "tablalistaproductos" data-role = "datatable">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Producto</th>
                    <th>Modelo</th>
                    <th>Color</th>
                </tr>
            </thead>
            <?php foreach ($ListaProductos->result() as $producto):
                ?>
                <tr>
                    <td><?= $producto->IdProductos ?></td>
                    <td><?= $producto->producto ?></td>
                    <td><?= $producto->modelo ?></td>
                    <td><?= $producto->color ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br><br>

    </div>
</div>
<div class="panel primary" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkBlue"></span>
        <span class="title">Imagenes de pedido</span>
    </div>
    <div class="content" id="" style="padding: 15px">
        <br>
        <div id="formulario_imagenes">
            <form action="SubirImagenPedido" enctype="multipart/form-data" method="post">
                <label>Selecciona la imagen:</label><br>
                <div class="input-control file" data-role="input">
                    <input type="file" name="userfile" />
                    <input type="hidden" name="pedidoid" value="<?= $pedidoid ?>">
                    <button class="button"><span class="mif-folder"></span></button>
                </div>
                <input type="submit" class="button primary" value="Subir imágen"/>
            </form>
        </div>
        <hr>
        <div class="grid">
            <div class="row cells5">
                <?php foreach ($ListaImagenes->result() as $imagen):
                    ?>
                    <div class="cell">
                        <img  src="<?= base_url() ?><?= $imagen->Ruta ?>"><br>
                        <a href="EliminarImagenPedido?idimagen=<?= $imagen->IdImagenesPedidos ?>&pedidoid=<?= $pedidoid ?>" class=" button danger">Eliminar</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<br><br>
<center>
    <div class="input-control text big-input medium-size" id="">
        <a class="button primary" href="CapturaPedidos">Regresar a Pedidos</a>
    </div>
    <div class="input-control text big-input medium-size" id="">
        <button class="button danger" onclick="MarcarSalidaProducto()">Salida de Pedido</button>
    </div>
</center>

