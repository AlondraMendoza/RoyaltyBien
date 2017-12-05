<script>
    function VerificarClave(){
        var clave=$("#claveGrif").val();
        $("#descripcion").html("Verificando clave");
        $.getJSON("VerificarClave", {"clave": clave}, function (data) {
            $("#descripcion").html(data.nombre);
            $("#idGrif").val(data.id);
        });
    }
    
    function Siguiente(){
        var id = $("#idGrif").val();
        var cantidad = $('#cantidad').val();
        $("#ResultadosGriferia").load("ResultadosGriferia", {"id": id,"cantidad": cantidad,"withouttem": 1});
        $("#Botones").fadeOut();
    }
    
    function Cancelar(){
        location.reload(true);
    }
</script>
<h1><b> CAPTURA DE GRIFERÍA</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar datos</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td style="width:20%" class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Clave de grifería:</b><br>
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
    <div id="ResultadosGriferia"></div>
</center><br><br><br>


