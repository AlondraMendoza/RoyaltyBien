<h1 class="light text-shadow">REPORTE DE QUEMA DE ACCESORIOS</h1><br>
<hr>
<div class="panel warning" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Periodo de tiempo</span>
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
        <br>
        <center><button onclick="Detalle()" class="button block-shadow-info text-shadow primary big-button">Siguiente</button></center>
        <br>
        <br>
    </div>    
</div>
<div id="detalle" class="shadow" ></div><br>
<script>
    function Detalle()
    {
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        $("#detalle").html("Cargando Información");
        $("#detalle").load("GenerarReporteQAcc", {"fechainicio": fechainicio, "fechafin": fechafin});
    }
</script>