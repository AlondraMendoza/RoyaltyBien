<script>

    function Detalle()
    {
        var pb = $("#pb2").data('progress');
        pb.set(100);
        $("#grafica").html("");
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        $("#detalle").show();
         $("#detalle").html("<center style='font-size:1.2em'><b>Consultando Información...</b></center><br><br>");
        $("#grafica").html("");
        $("#detalleseleccionado").hide();
        $("#divtiporeporte").hide();
        $("#detalle").load("GenerarReporteQ", {"fechainicio": fechainicio, "fechafin": fechafin}); 
    
    }

    function Paso(paso)
    {
        $("#paso1").hide();
        $("#paso2").hide();
        $("#paso3").hide();
        $("#paso4").hide();
        $("#paso5").hide();
        $("#paso6").hide();
        var pb = $("#pb2").data('progress');
        var porc = paso * 15;
        pb.set(porc);
        $("#porc").html(porc + "%");
        $("#paso" + paso).show();
    }
</script>
<h1 class="light text-shadow">REPORTE DE HORNOS</h1><br>
<div class="progress large" id="pb2" data-parts="true" data-role="progress" data-value="0" data-colors="{&quot;bg-red&quot;: 33, &quot;bg-yellow&quot;: 66, &quot;bg-cyan&quot;: 90, &quot;bg-green&quot;: 100}"><div class="bar bg-green" style="width: 100%;height: 30px"></div></div>
<hr>
<div class="panel warning" data-role="panel" id="paso1">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Paso 1: Periodo de tiempo</span>
    </div>
    <div class="content">
        <table class="table shadow" >
            <thead>
                <tr>
                    <th class="align-center">Fecha Inicio</th>
                    <th class="center">Fecha Fin</th>
                </tr>
            </thead>
            <tr>
                <td class="center">
                    <div class="input-control text full-size" data-role="datepicker" data-locale="es" data-format="dd/mm/yyyy" id="datepicker">
                        <input type="text" id="fechainicio" value="<?= $hoy ?>">
                        <button class="button"><span class="mif-calendar"></span></button>
                    </div>
                </td>
                <td class="center">
                    <div class="input-control text full-size" data-role="datepicker" data-locale="es" data-format="dd/mm/yyyy" id="datepicker">
                        <input type="text" id="fechafin" value="<?= $hoy ?>">
                        <button class="button"><span class="mif-calendar"></span></button>
                    </div>
                </td>
            </tr>
        </table>

        <center> 
        <button onclick="Paso(6)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>

<div class="panel warning" data-role="panel" id="paso6" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Paso 6: Tipo de Reporte</span>
    </div>
    <div class="content">
        <br>
        <div id="divtiporeporte">
        <table class="table shadow">
            <thead>
                <tr>
                    <th colspan="3" class="center fg-darkBlue">TIPO DE REPORTE</th>
                </tr>
                <tr>
                    <th>Lista Detalle</th>
                    <!--<th>Concentrado y Graficado Por:</th>-->
                </tr>
            </thead>
            <tr>
                <td class="center">
                    <i>Se mostrarán los productos a detalle</i><br>
                    <button id="" style="" class="button block-shadow-info text-shadow primary big-button" onclick="Detalle()"><span class="mif-arrow-right mif-ani-hover-horizontal"></span> Consultar</button>
                </td>

            </tr>
        </table>
        <center>
            <button onclick="Paso(1)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
        </center>
        </div>
        <div id="detalle" class="shadow" ></div><br><br><div id="texto"></div>
        <div id='grafica'>
            <canvas id="myChart" width="300" height="100" class="shadow"></canvas>
        </div>
        <div id="detalleseleccionado"> </div>
           </div>
</div>
        