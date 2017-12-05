<script>
    function Siguiente()
    {
        var fecha = $("#fecha").val();
        $("#ResultadosAccesorios").load("ResultadosAccesorios", {"fecha": fecha, "withouttem": 1});
        $("#Botones").fadeOut();
    }
    
    function Cancelar()
    {
        location.reload(true);
    }
    
</script>
<h1><b> CAPTURA DE ACCESORIOS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar datos</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                    <tr>
                        <td style="width: 32%" class="center">
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Producto:</b><br> 
                            <div data-role="group" data-group-type="one-state">   
                            <button class="button" style='width: 210px; height: 210px;'>
                            <input type="image" src="http://localhost/RoyaltyBien/public/imagenes/Accesorios.jpg" height="190px;" width="190px;" title="Accesorios"/><b>
                                Accesorios</b></button>
                            </div>
                        </td>
                        <td style="width: 32%" class="center"><br><br><br><br><br>
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Fecha de quemado:</b><br>
                            <div class="input-control text big-input full-size" data-role="datepicker" id="datepicker" data-locale="es" data-format="dd-mm-yyyy" style="height:80px;font-size: x-large">
                                <input type="text" id="fecha">
                                <button class="button"><span class="mif-calendar"></span></button>
                            </div>
                        </td>
                        <td class="center" id="Botones"><br><br><br><br><br><br>
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
    <div id="ResultadosAccesorios"></div>
</center><br><br><br>

