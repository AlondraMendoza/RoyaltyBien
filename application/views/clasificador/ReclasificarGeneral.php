<script>
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
                        $("#des").html("Producto encontrado en devoluciones");
                        $("#panelbusqueda").fadeOut();
                        $("#tablareclasificar").fadeIn();
                        $("#tablareclasificar").fadeIn();
                        $("#tablareclasificar").load("TablaProductosReclasificar", {"producto": data.id});
                    } else
                    {
                        $("#des").html("No se encontró producto en devoluciones sin procesar");
                    }
                });
            }
        }
    }
    function Cancelar() {
        location.reload(true);
    }
</script>
<h1 class="light text-shadow">RECLASIFICAR DEVOLUCIÓN</h1><br>
<center>
    <div class="panel warning" data-role="panel" id="panelbusqueda">
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
                            <input type="text" id="claveProd" onkeyup="VerificarClave(event)">
                        </div>
                        <br><label><span id="des"></span></label>
                    </td> 
                </tr>
                <br><br>
            </table>
        </div>
    </div>
    <center>
        <div id="tablareclasificar" style="display:none"></div>
    </center>
</center><br><br><br>


