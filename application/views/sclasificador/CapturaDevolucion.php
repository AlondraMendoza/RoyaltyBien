<script>
    var guardado = 0;
    function Guardar() {
        if (guardado == 0) {
            /*
             * 
             * if ( $("#undiv").length ) {
             */
            var cliente = $("#cliente").val();
            var motivo = $("#motivo").val();
            var responsable = $("#responsable").val();
            if (cliente == "" || motivo == "" || responsable == "") {
                $.Notify({
                    caption: 'Error',
                    content: 'Es necesario capturar el cliente, responsable y motivo',
                    type: 'alert'
                });
            } else
            {
                $.post("GuardarDevolucion", {"cliente": cliente, "motivo": motivo, "responsable": responsable}, function (data) {
                    var iddevolucion = data;
                    $("input[name='IDS[]']:checked").each(function () {
                        var id = $(this).val();

                        $.post("GuardarDetalleDevolucion", {"idproducto": $(this).val(), "iddevolucion": iddevolucion}, function (data) {
                            if (data == "Correcto")
                            {
                                $("#td" + id).html('<span class="mif-checkmark fg-green"></span> Producto Guardado');
//                        $.Notify({
//                            caption: 'Correcto',
//                            content: 'Los productos se guardarón correctamente',
//                            type: 'success'
//                        });
                            } else if (data == "Existe")
                            {
                                $("#td" + id).html("<span class='mif-cancel fg-red'></span> El producto ya se encuentra guardado<br> en alguna devolución sin procesar");
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

                //$("#tablaproductos").empty();
                guardado = 1;
                $("#botonguardar").fadeOut();
                $("#nuevadevolucion").fadeIn();
            }
        }
    }
    function Cancelar() {
        location.reload(true);
    }
    function VerificarClave(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProd").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des").html("Verificando clave...");
                $.getJSON("VerificarClaveProd", {"clave": clave}, function (data) {
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
                            $("#informacionadicional").fadeIn();
                            $("#ResultadosTarimas").fadeIn();
                        }
                    } else
                    {
                        $("#des").html("No se encontró producto");
                    }
                });
            }
        }
    }
</script>
<h1 class="light text-shadow">CAPTURA DEVOLUCIÓN</h1><br>
<center>   
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Agregar Producto</span>
        </div>
        <div class="content">
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
            <br><br>
        </div>
    </div>
    <div id="ResultadosTarimas" style="display:none">
        <center>
            <div class="panel primary" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkBlue"></span>
                    <span class="title">Detalle de Productos agregados</span>
                </div>
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

                </div>
            </div>
        </center>
    </div>
    <div id="informacionadicional" style="display: none">
        <div class="panel success" data-role="panel">
            <div class="heading">
                <span class="icon mif-stack fg-white bg-darkGreen"></span>
                <span class="title">Información adicional</span>
            </div>
            <div class="content" id="">
                <table class="table hovered bordered border">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Motivo</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                    <input type="text" id="cliente">
                                </div>
                            </td>
                            <td>
                                <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                    <input type="text" id="motivo">
                                </div>
                            </td>
                            <td>
                                <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                    <input type="text" id="responsable">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table>
            <tr>
                <td class="center" style="width: 220px" id="botonguardar">
                    <div class="input-control text big-input medium-size" >
                        <button class="button primary" onclick="Guardar()" style="width: 200px">Guardar</button>
                    </div> 
                </td>
                <td class="center" style="width: 220px;display: none" id="nuevadevolucion">
                    <div class="input-control text big-input medium-size">
                        <button class="button warning" onclick="Cancelar()" style="width: 200px">Nueva devolución</button>
                    </div> 
                </td>
                <td class="center" style="width: 220px">
                    <div class="input-control text big-input medium-size">
                        <button class="button danger" onclick="Cancelar()" style="width: 200px">Cancelar</button>
                    </div> 
                </td>
            </tr>
        </table>
    </div>


