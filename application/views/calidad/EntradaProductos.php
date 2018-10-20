<h1><b> ENTRADA DE PRODUCTOS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar Código de Barras</span>
        </div>
        <div class="content" id="Inicio2">
            <table class="table">
                <tr>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="claveProdP" onkeyup="VerificarClave(event)">
                        </div>
                         <br><label><span id="des2P"></span></label>
                    </td> 
                </tr>
                    <br><br>
                </table>
            </div>
        </div>
    <div id="resultadostarimasP" style="display: none">
        <center>
    <div class="panel primary" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Detalle de Productos</span>
        </div>
        <div class="content" id="Resultados2P">
        <table class="table bordered border hovered" id="tablatarimasP">
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
            <td class="center" id="BotonesP"><br>
                <div class="input-control text big-input medium-size" id="nuevatarima2P" style="display: none"><button class="button warning" onclick="CancelarP()">Nueva Entrada</button></div>
                <div class="input-control text big-input medium-size" id="botonguardar2P"><button class="button success" onclick="GuardarP()">Guardar</button></div>
                <div class="input-control text big-input medium-size"><button class="button danger" onclick="CancelarP()">Cancelar</button></div>
            </td>
        </tr>
        </table>
        </div>
    </div>
</center>
    </div>
</center><br>   


<script>   
    function VerificarClave(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProdP").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des2P").html("Verificando clave de producto...");
                $.getJSON("VerificarClave", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#des2P").html("Producto encontrado");
                        if ($("#td2" + data.id).length)
                        {
                            $.Notify({
                                caption: 'Error',
                                content: 'Ya agregaste el producto a la lista',
                                type: 'alert'
                            });
                            $("#claveProdP").val("");
                            $("#des2P").html("");
                        } else {
                            //metodo para Abrir tabla y agregar datos
                            var input = '<tr><td class="center">' + data.id + '</td><td class="center">';
                            input += '<b style="font-size: 1.3em" class="fg-darkEmerald">Descripción:</b><br>';
                            input += data.nombre;
                            input += '</td>';
                            input += '<td class="center" id="td2' + data.id + '"><label class="input-control checkbox">';
                            input += '<input type="checkbox" name="IDS2P[]" value="' + data.id + '" checked>';
                            input += '<span class="check"></span>';
                            input += '</label></td></tr>';
                            $("#tablatarimasP").append(input);
                            $("#claveProdP").val("");
                            $("#des2P").html("");
                            $("#resultadostarimasP").fadeIn();
                        }
                    } else
                    {
                        $("#des2P").html("El producto no es clasificación D");
                    }
                });
            }
        }
     }

var guardadoP = 0;
    function GuardarP() {
        if (guardadoP == 0) {
            $("input[name='IDS2P[]']:checked").each(function () {
                var id = $(this).val();
                $.post("GuardarProd", {"idProducto": id}, function (data) {
                    if (data == "Correcto")
                    {
                       $("#td2" + id).html('<span class="mif-checkmark fg-green"></span> Producto Guardado');
                    } else if (data == "Existe")
                    {
                       $("#td2" + id).html("<span class='mif-cancel fg-red'></span> El producto ya se encuentra <br>en el Almacén ");
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
            guardadoP = 1;
            $("#Inicio2").fadeOut();
            $("#botonguardar2P").fadeOut();
            $("#nuevatarima2P").fadeIn();
        }
        //$("#tablaproductos").empty();
    }
    
    function CancelarP() {
        location.reload(true);
    }
</script>