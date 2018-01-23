<script>    
    function VerificarClaveTarima(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProd").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des2").html("Verificando clave de tarima...");
                $.getJSON("VerificarClaveTarimaAlmacen", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#des2").html("Tarima encontrada");
                        if ($("#td2" + data.id).length)
                        {
                            $.Notify({
                                caption: 'Error',
                                content: 'Ya agregaste la tarima a la lista',
                                type: 'alert'
                            });
                            $("#claveProd").val("");
                            $("#des2").html("");
                        } else {
                            //metodo para Abrir tabla y agregar datos
                            var input = '<tr><td class="center">' + data.id + '</td>';
                            input += '</td>';
                            input += '<td class="center" id="td2' + data.id + '"><label class="input-control checkbox">';
                            input += '<input type="checkbox" name="IDS2[]" value="' + data.id + '" checked>';
                            input += '<span class="check"></span>';
                            input += '</label></td></tr>';
                            $("#tablatarimas").append(input);
                            $("#claveProd").val("");
                            $("#des2").html("");
                            $("#resultadostarimas").fadeIn();
                        }
                    } else
                    {
                        $("#des2").html("No se encontró la tarima");
                    }
                });
            }
        }
    }
   

var guardado = 0;
    function SalidaTarimas() {
        if (guardado == 0) {
            $("input[name='IDS2[]']:checked").each(function () {
                var id = $(this).val();
                $.post("SalirTarimasAlmacen", {"idtarima": id}, function (data) {
                    if (data == "Correcto")
                    {
                        $("#td2" + id).html('<span class="mif-checkmark fg-green"></span> Tarima fuera del almacén');
                    } else
                    {
                        $.Notify({
                            caption: 'Error',
                            content: 'Ocurrió un error con la tarima',
                            type: 'alert'
                        });
                    }
                });
            });
            guardado = 1;
            $("#botonguardar2").fadeOut();
            $("#nuevatarima2").fadeIn();
        }
        //$("#tablaproductos").empty();
    }
    
    function Cancelar() {
        location.reload(true);
    }
</script>
<h1><b> SALIDA DE TARIMAS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar Código de Barras</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="claveProd" onkeyup="VerificarClaveTarima(event)">
                        </div>
                         <br><label><span id="des2"></span></label>
                    </td> 
                </tr>
                    <br><br>
                </table>
            </div>
        </div>
    <div id="resultadostarimas">
        <center>
    <div class="panel primary" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Detalle de Tarimas</span>
        </div>
        <div class="content" id="Resultados2">
        <table class="table bordered border hovered" id="tablatarimas">
            <thead>
             <tr>
                <th>Clave</th>
                <th>Seleccion/Acción</th>
             </tr>
           </thead>
        </table>
        <table>
        <tr>
            <td class="center" id="Botones"><br>
                <div class="input-control text big-input medium-size" id="nuevatarima2" style="display: none"><button class="button warning" onclick="Cancelar()">Nueva Salida</button></div>
                <div class="input-control text big-input medium-size" id="botonguardar2"><button class="button success" onclick="SalidaTarimas()">Guardar</button></div>
                <div class="input-control text big-input medium-size"><button class="button danger" onclick="Cancelar()">Cancelar</button></div>
            </td>
        </tr>
        </table>
        </div>
    </div>
</center>
    </div>
</center><br><br><br>




