<script>

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
        $.post("GuardarDetallePedidoCedis", {"idproducto": id, "idpedido": "<?= $pedidoid ?>"}, function (data) {
            if (data == "Correcto")
            {
                $.Notify({
                    caption: 'Correcto',
                    content: 'El producto se agregó al pedido correctamente',
                    type: 'success'
                });
                $("#desp").html("El producto se agregó correctamente al pedido");
                $("#claveProdp").val("");
                CargarPedidoVentas();
                RecargaProductosPedido();

            } else if (data == "En pedido")
            {
                $.Notify({
                    caption: 'Error',
                    content: 'El producto ya se encuentra en un pedido',
                    type: 'alert'
                });
            } else if (data == "Fuera límite") {
                $.Notify({
                    caption: 'Error',
                    content: 'El producto que deseas agregar está fuera del límite de pedido configurado',
                    type: 'alert'
                });
            } else if (data == "No solicitado") {
                $.Notify({
                    caption: 'Error',
                    content: 'El producto que deseas agregar no fue solicitado',
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

        //$("#tablaproductos").empty();
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
    function CargarPedidoVentas()
    {
        $("#cellpedidoventas").load("InformacionPedidoVentas", {"pedidoid": "<?= $pedido->IdPedidos ?>"});
    }
    function RecargaProductosPedido()
    {
        $("#productosenpedido").load("RecargaProductosPedido", {"pedidoid": "<?= $pedido->IdPedidos ?>"});
    }
    $(document).ready(function () {
        CargarPedidoVentas();
        RecargaProductosPedido();
    });
</script>
<h1><b> INFORMACIÓN DE PEDIDO: <?= $pedidoid ?></b></h1><br>
<div class="grid">
    <div class="row cells2">
        <div class="cell ">
            <div class="panel  fg-white" data-role="panel">
                <div class="heading bg-green">
                    <span class="icon mif-stack fg-white bg-darkGreen"></span>
                    <span class="title">Ingresar Código de Barras del Producto</span>
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
                    <div id="productosenpedido"></div>
                    <br><br>
                </div>
            </div>

        </div>
        <div class="cell" id="cellpedidoventas"></div>
    </div>
</div>


<br><br>
<div class="panel primary" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkBlue"></span>
        <span class="title">Información adicional para pedido</span>
    </div>
    <div class="content" id="" style="padding: 15px">
        <br>
        <div id="formulario_imagenes">
            <form action="SubirImagenPedido" enctype="multipart/form-data" method="post">
                <center><label>Selecciona la imagen:</label><br>
                    <div class="input-control file full-size" data-role="input">
                        <input type="file" name="userfile" />
                        <input type="hidden" name="pedidoid" value="<?= $pedidoid ?>">
                        <button class="button"><span class="mif-folder"></span></button>
                    </div>
                    <br><br>
                    <label>Teclea la observación:</label><br>
                    <div class="input-control textarea full-size">
                        <textarea name="observacioncedis"><?= $pedido->ObservacionSalida ?></textarea>
                    </div><br>
                    <input type="submit" class="button primary" value="Guardar Cambios"/>
                </center>
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
        <a class="button warning" href="ReportePedido?idpedido=<?= $pedido->IdPedidos?>">Descargar Formato</a>
    </div>
    <div class="input-control text big-input medium-size" id="">
        <a class="button primary" href="CapturaPedidos">Regresar a Pedidos</a>
    </div>
    <div class="input-control text big-input medium-size" id="divbotonsalida">
    </div>
</center>

<br><br><br>