<script>
    $(document).ready(function () {
    });

    function Consultar()
    {
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        $("#detalle").html("<center style='font-size:1.2em'><b>Consultando Información...</b></center><br><br>");
        $("#detalle").load("CargarClasificacionesTrabajador", {"fechainicio": fechainicio, "fechafin": fechafin})
    }
</script>
<h1 class="light text-shadow">CLASIFICACIONES POR TRABAJADOR</h1><br>
<hr>
<div class="panel warning" data-role="panel" id="paso1">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Selección de fechas de clasificación</span>
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
        <center><button onclick="Consultar()" class="button block-shadow-info text-shadow primary big-button">Consultar Clasificaciones</button></center>
        <br>
        <br><br><br><br>
    </div>
</div>
<br>
<div class="grid">
    <div class="row cells1" id="cambioceldas">
        <div class="cell" id="detalle"></div>
    </div>
</div>


