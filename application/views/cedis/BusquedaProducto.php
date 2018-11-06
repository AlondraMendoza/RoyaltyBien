<script>
    function Redireccionar(id)
    {
        var liga = 'expedienteproducto?producto_id=' + id;
        window.location = liga;
    }

    function VerificarClave(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code != 13 && code != 16 && code != 17 && code != 18) { //Enter keycode
            var cadena = $("#claveProd").val();
            if (cadena.length == 19) {
                var inicio = 9;
                var clave = cadena.substring(inicio);
                $("#des").html("Verificando clave...");
                $.getJSON("VerificarClaveProdExpediente", {"clave": clave}, function (data) {
                    if (data.id != null) {
                        $("#des").html("Producto encontrado");

                        //metodo para Abrir tabla y agregar datos
                        var input = '<tr><td class="center">' + data.id + '</td><td class="center">';
                        input += '<b style="font-size: 1.3em" class="fg-darkEmerald">Descripción:</b><br>';
                        input += data.nombre;
                        input += '</td>';
                        input += '<td class="center" id="td' + data.id + '">';
                        input += '<div class="input-control text big-input medium-size" id="botonguardar">';
                        input += '<button class="button success" onclick="Redireccionar(' + data.id + ')">Expediente</button></div>';
                        input += '</td></tr>';
                        $("#tablaproductos").html(input);
                        $("#claveProd").val("");
                        $("#des").html("");
                        $("#ResultadosBusqueda").fadeIn();
                    } else
                    {
                        $("#des").html("No se encontró producto");
                    }
                });
            }
        }
    }
</script>
<h1><b> BÚSQUEDA DE PRODUCTOS</b></h1><br>
<center>
    <div class = "panel warning" data-role = "panel">
        <div class = "heading">
            <span class = "icon mif-stack fg-white bg-darkOrange"></span>
            <span class = "title">Ingresar Código de Barras</span>
        </div>
        <div class = "content" id = "Inicio">
            <table class = "table">
                <tr>
                    <td class = "center">
                        <b style = "font-size: 1.3em" class = "fg-darkEmerald"> Código de Barras:</b><br>
                        <div class = "input-control text full-size" style = "height:80px;font-size: x-large">
                            <input type = "text" id = "claveProd" onkeyup = "VerificarClave(event)">
                        </div>
                        <br><label><span id = "des"></span></label>
                    </td>
                </tr>
                <br><br>
            </table>
        </div>
    </div>
    <div id="ResultadosBusqueda" style="display: none">
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