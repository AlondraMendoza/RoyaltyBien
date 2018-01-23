<script>
     $(document).ready(function () {
        $("#fecha").change(function () {
            $("#tdfecha").html($("#fecha").val());
            $("#tdproducto").html("Accesorios");
        });
    });
    function Siguiente()
    {
        var fecha = $("#fecha").val();
        if(fecha == ""){
            Notificacion("Error", "Selecciona la fecha antes de continuar", "cancel", "alert");
            return(0);
        }
        $.get("ResultadosAccesorios", {"fecha": fecha, "withouttem": 1}, function(){
            $("#Lista").fadeIn();
            $("#ListaTabla").append("<tr>"+$("#trprevia").html()+"</tr>");
            $("#tdproducto").html("");
            $("#tdfecha").html(""); 
        });
        $("#fecha").val("");
    }
    
    function Notificacion(titulo, texto, icono, color)
    {
        $.Notify({
            caption: titulo,
            content: texto,
            icon: "<span class='mif-" + icono + "'></span>",
            type: color
        });
    }
    
    function Cancelar()
    {
        $("#fecha").val("");
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
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Producto:</b><br> <br><br>
                            <div data-role="group" data-group-type="one-state">   
                            <button class="button" style='width: 210px; height: 210px;' id="Prod">
                            <input type="image" src="<?php echo base_url() ?>public/imagenes/Accesorios.jpg" height="190px;" width="190px;" title="Accesorios"/><b>
                                Accesorios</b></button>
                            </div><br><br><br><br><br><br><br><br><br><br>
                        </td>
                        <td style="width: 32%" class="center">
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Fecha de quemado:</b><br>
                            <div class="input-control text big-input full-size" data-role="datepicker" id="datepicker" data-locale="es" data-format="dd-mm-yyyy" style="height:80px;font-size: x-large">
                                <input type="text" id="fecha">
                                <button class="button"><span class="mif-calendar"></span></button>
                            </div>
                        </td>
                        <td class="center" id="Botones">
                            <div class="input-control text big-input medium-size">
                            <button class="button success" onclick="Siguiente()">Siguiente</button></div>
                            <div class="input-control text big-input medium-size">
                            <button class="button danger" onclick="Cancelar()">Cancelar</button></div>
                        </td>
                    </tr>
                    <br><br>
                </table>
            </div>
        </div>
    <div class="panel primary" data-role="panel" id="" style="z-index: 1">
        <div class="heading" style="position:relative;z-index: 1">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Detalle de Carro</span>
        </div>
        <div class="content" id="">
            <div id="Resultados">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto:</th>
                            <th>Fecha Quemado:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="trprevia">
                            <td id="tdproducto"></td>
                            <td id="tdfecha"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
        </div>
    </div>
    <br>
    <div class="panel success" data-role="panel" style="display: none;" id="Lista">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkGreen"></span>
            <span class="title">Detalle de capturas</span>
        </div>
        <div class="content" id="">
            <table class="table" id="ListaTabla">
                <thead>
                    <tr>
                        <th>Producto:</th>
                        <th>Fecha Quemado:</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</center><br><br><br>

