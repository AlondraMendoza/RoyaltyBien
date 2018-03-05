<script>
    var existencia=0;
    function VerificarClave(){
        var clave=$("#claveGrif").val();
        $("#descripcion").html("Verificando clave");
        $.getJSON("VerificarClaveExistencia", {"clave": clave}, function (data) {
            $("#descripcion").html(data.nombre);
            $("#existencia").html(data.existencia);
            existencia= data.existencia;
            $("#idGrif").val(data.id);
        });
    }
    
    function Siguiente(){
        var id = $("#idGrif").val();
        var cantidad = $('#cantidad').val();
        if(cantidad > existencia){
            $.Notify({
                caption: 'Error',
                content: 'No tienes suficiente grifería',
                type: 'alert'
            });
            alert(cantidad);
            alert(existencia);
        }else{
            $.post("ResultadosGriferiaSalida", {"id": id, "cantidad":cantidad }, function (data) {
                    if (data == "Correcto")
                    {
                        $.Notify({
                            caption: 'Bien',
                            content: 'Se guardó exitosamente la salida',
                            type: 'success'
                        });
                        existencia=0;
                    } else
                    {
                        $.Notify({
                            caption: 'Error',
                            content: 'Ocurrió un error',
                            type: 'alert'
                        });
                        existencia=0;
                    }
                });
                $("#s").fadeOut();
                $("#s1").fadeOut();
                $("#nueva").fadeIn();
        }
    }
    
    function Cancelar(){
        location.reload(true);
    }
</script>
<h1><b>SALIDA DE GRIFERÍA</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar datos</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td style="width:15%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Clave de grifería:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="claveGrif" onkeyup="VerificarClave()" placeholder="Ejemplo.- GRI10000">
                        <input type="hidden" id="idGrif">
                        </div>
                    </td>
                    <td style="width:36%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Descripción:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                        <br><span id="descripcion"></span>
                        </div>
                    </td>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Existencia:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                        <br><span id="existencia"></span>
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
                            <button class="button primary" id="s" onclick="Siguiente()">Siguiente</button></div>
                            <div class="input-control text big-input medium-size">
                            <button class="button danger" id="s1" onclick="Cancelar()">Cancelar</button></div>
                            <div class="input-control text big-input medium-size" id="nueva" style="display: none">
                            <button class="button warning" onclick="Cancelar()">Nueva Salida</button></div>
                    </td>
                </tr>
                    <br><br>
                </table>
            </div>
        </div>
    <div id="ResultadosGriferia"></div>
</center><br><br><br>




