<script>
    function VerificarClave() {
        var clave = $("#claveGrif").val();
        $("#descripcion").html("Verificando clave");
        $.getJSON("VerificarClave", {"clave": clave}, function (data) {
            $("#descripcion").html(data.nombre);
            $("#idGrif").val(data.id);
        });
    }
    function Siguiente() {
        $("#resultadossubproductos").fadeIn();
        var id = $("#idGrif").val();
        var clave = $("#claveGrif").val();
        var cantidad = $('#cantidad').val();
        var nombre = $("#descripcion").html();
        if ($("#td" + id).length)
        {
            $.Notify({
                caption: 'Error',
                content: 'Ya agregaste el subproducto a la lista',
                type: 'alert'
            });
            $("#claveGrif").val("");
            $("#descripcion").html("");
        } else {
            //metodo para Abrir tabla y agregar datos
            var input = '<tr>';
            input += '</td>';
            input += '<td class="center" id="td' + id + '"><label class="input-control checkbox">';
            input += '<input type="checkbox" name="IDS[]" value="' + id + '" checked>';
            input += '<span class="check"></span>';
            input += '</label></td><td class="center">' + clave + '</td><td>' + nombre + '</td><td class="center"><div class="input-control text full-size"><input type="number" value="' + cantidad + '" id="cantidad' + id + '"></td></tr>';
            $("#tablasubproductos").append(input);
            $("#claveGrif").val("");
            $('#cantidad').val("");
            $("#descripcion").html("");
        }
    }
    var guardado = 0;
    function GuardarSubProductos() {
        if (guardado == 0) {
            $("input[name='IDS[]']:checked").each(function () {
                var id = $(this).val();
                var cantidad = $("#cantidad" + id).val();
                $.post("GuardarSubproductosAlmacen", {"idsubproducto": id, "cantidad": cantidad}, function (data) {
                    if (data == "correcto")
                    {
                        $("#td2" + id).html('<span class="mif-checkmark fg-green"></span> Subproducto Guardada');
                    } else
                    {
                        $.Notify({
                            caption: 'Error',
                            content: 'Ocurrió un error al guardar el subproducto',
                            type: 'alert'
                        });
                    }
                });
                $("#cantidad" + id).prop('disabled', true);
                $(this).prop('disabled', true);
            });
            guardado = 1;

            $("#botonguardar").fadeOut();
            $("#Botones").fadeOut();
            $("#nuevosub2").fadeIn();
            $("#panelcaptura").fadeOut();
        }
        //$("#tablaproductos").empty();
    }
    function Siguientebak() {
        var id = $("#idGrif").val();
        var cantidad = $('#cantidad').val();
        $("#ResultadosGriferia").load("ResultadosGriferia", {"id": id, "cantidad": cantidad, "withouttem": 1});
        $("#Botones").fadeOut();

    }

    function Cancelar() {
        location.reload(true);
    }
</script>
<h1><b> CAPTURA DE SUBPRODUCTOS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel" id="panelcaptura">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar datos</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td style="width:20%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Clave de Subproducto:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="claveGrif" onkeyup="VerificarClave()" placeholder="Ejemplo.- GRI10000">
                            <input type="hidden" id="idGrif">
                        </div>
                    </td>
                    <td style="width:40%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Descripción:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <br><span id="descripcion"></span>
                        </div>
                    </td>
                    <td style="width:5%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald">Cantidad:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="cantidad">
                        </div>
                    </td>
                    <td class="center" id="Botones"><br>
                        <div class="input-control text big-input medium-size">
                            <button class="button primary" onclick="Siguiente()">Siguiente</button></div>
                        <div class="input-control text big-input medium-size">
                            <button class="button danger" onclick="Cancelar()">Cancelar</button></div>
                    </td>
                </tr>
                <br><br>
            </table>
        </div>
    </div>
    <div id="resultadossubproductos" style="display: none">
        <center>
            <div class="panel primary" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkBlue"></span>
                    <span class="title">Detalle de Subproductos agregados</span>
                </div>
                <div class="content" id="Resultados2">
                    <table class="table bordered border hovered" id="tablasubproductos">
                        <thead>
                            <tr>
                                <th>Seleccion/Acción</th>
                                <th>Clave</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                    </table>
                    <table>
                        <tr>
                            <td class="center" id="Botones"><br>
                                <div class="input-control text big-input medium-size" id="nuevosub2" style="display: none"><button class="button warning" onclick="Cancelar()">Nueva Entrada</button></div>
                                <div class="input-control text big-input medium-size" id="botonguardar"><button class="button success" onclick="GuardarSubProductos()">Guardar</button></div>
                                <div class="input-control text big-input medium-size"><button class="button danger" onclick="Cancelar()">Cancelar</button></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </center>
    </div>
</center><br><br><br>


