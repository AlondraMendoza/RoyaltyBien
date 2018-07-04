<script>
    function Cancelar() {
        location.reload(true);
    }
</script>
<h1><b> DEVOLUCIONES CAPTURADAS </b></h1><br>
<center>

    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Lista de devoluciones procesadas</span>
        </div>
        <div class="content">
            <table class="table shadow" >
                <thead>
                    <tr>
                        <th class="align-center">Fecha Inicio</th>
                        <th class="center">Fecha Fin</th>
                        <th class="center">Consultar</th>
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
                    <td class="center">
                        <div class="input-control text big-input medium-size" id="botonguardar">
                            <button class="button success" onclick="CargarDevoluciones()">Consultar
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
            <div id="devolucionesconsultadas"></div>
        </div>
    </div>
</center>
<br><br><br>
<script>
    function CargarDevoluciones()
    {
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        $("#devolucionesconsultadas").load("DevolucionesCapturadas", {"fechainicio": fechainicio, "fechafin": fechafin});
    }
    $(document).ready(function () {
        CargarDevoluciones();
    });
</script>
