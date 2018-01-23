<script>
    /*
    function VerificarClave(){
        var cadena=$("#claveProd").val();
        if(cadena.length==19){
        var inicio = 9;
        var clave = cadena.substring(inicio);
        $("#des").html("Verificando clave");
        $.getJSON("VerificarClaveProd", {"clave": clave}, function (data) {
            if(data.id != null){
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
                var input='<tr><td class="center">';
                input+='<b style="font-size: 1.3em" class="fg-darkEmerald">Descripción:</b><br>';
                input+=data.nombre;
                input+='</td>';
                input+='<td><label class="input-control checkbox">';
                input+='<input type="checkbox" name="IDS[]" value="'+data.id+'" checked>';
                input+='<span class="check"></span>';
                input+='</label></td></tr>';
                $("#tablaproductos").append(input);
                $("#claveProd").val("");
            }
            }   
        });
        }
        }*/
    
    function VerificarClaveTarima(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProd").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des2").html("Verificando clave de tarima...");
                $.getJSON("VerificarClaveTarima", {"clave": clave}, function (data) {
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
    /*
    function Guardar(){
        $("input[name='IDS[]']:checked").each(function() {
            //alert($(this).val());
            $.post("GuardarAlmacen", {"id": $(this).val()}, function (data) {
            if (data == "bien")
            {
                $.Notify({
                caption: 'Correcto',
                content: 'Los productos se guardarón correctamente',
                type: 'success'
                });
            } else
            {
                $.Notify({
                caption: 'Error',
                content: 'Ocurrió un error al guardar los productos',
                type: 'alert'
                });
            }
        });
        });
        $("#tablaproductos").empty();
    }*/

var guardado = 0;
    function GuardarTarimas() {
        if (guardado == 0) {
            $("input[name='IDS2[]']:checked").each(function () {
                var id = $(this).val();
                $.post("GuardarTarimasAlmacen", {"idtarima": id}, function (data) {
                    if (data == "Correcto")
                    {
                        $("#td2" + id).html('<span class="mif-checkmark fg-green"></span> Tarima Guardada');
                    } else if (data == "Existe")
                    {
                       $("#td2" + id).html("<span class='mif-cancel fg-red'></span> La tarima ya se encuentra <br>en el Almacén ");
                    } else
                    {
                        $.Notify({
                            caption: 'Error',
                            content: 'Ocurrió un error al guardar la tarima',
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
<h1><b> ENTRADA DE TARIMAS</b></h1><br>
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
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
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
                <div class="input-control text big-input medium-size" id="nuevatarima2" style="display: none"><button class="button warning" onclick="Cancelar()">Nueva Entrada</button></div>
                <div class="input-control text big-input medium-size" id="botonguardar2"><button class="button success" onclick="GuardarTarimas()">Guardar</button></div>
                <div class="input-control text big-input medium-size"><button class="button danger" onclick="Cancelar()">Cancelar</button></div>
            </td>
        </tr>
        </table>
        </div>
    </div>
</center>
    </div>
</center><br><br><br>


