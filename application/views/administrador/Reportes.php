<script>
    $(document).ready(function () {
        ObtenerModelos();
        //Grafica("Productos");
    });
    function ObtenerModelos()
    {
        $("#modelo").html("<option>Cargando...</option>");
        var producto = $("#producto").val();
        $.get("ObtenerModelos", {"producto": producto}, function (data) {
            $("#modelo").html(data);
            ObtenerColores();
        });
    }
    function ObtenerColores()
    {
        $("#color").html("<option>Cargando...</option>");
        var modelo = $("#modelo").val();
        $.get("ObtenerColores", {"modelo": modelo}, function (data) {
            $("#color").html(data);
        });
    }
    function Detalle()
    {
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        var clasificacion = $("#clasificacion").val();
        var producto = $("#producto").val();
        var modelo = $("#modelo").val();
        var color = $("#color").val();
        $("#detalle").html("Cargando Información");
        $("#detalle").load("GenerarReporte", {"fechainicio": fechainicio, "fechafin": fechafin, "clasificacion": clasificacion, "producto": producto, "modelo": modelo, "color": color})
    }
    function Concentrado()
    {
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        var clasificacion = $("#clasificacion").val();
        var producto = $("#producto").val();
        var modelo = $("#modelo").val();
        var color = $("#color").val();
        var por = $("#concentradox").val();
        $("#detalle").html("Cargando Información");
        $("#detalle").load("GenerarConcentrado", {"fechainicio": fechainicio, "fechafin": fechafin, "clasificacion": clasificacion, "producto": producto, "modelo": modelo, "color": color, "por": por});
    }
</script>
<h1 class="light text-shadow">REPORTES</h1><br>
<div class="panel warning" data-role="panel">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Información para generar reporte</span>
    </div>
    <div class="content">
        <table class="table shadow">
            <thead>
                <tr>
                    <th class="align-center fg-darkBlue" colspan="2">PERIODO DE TIEMPO</th>
                </tr>
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
        <table class="table shadow">
            <thead>
                <tr>
                    <th class="align-center fg-darkBlue" colspan="4">FILTROS</th>
                </tr>
                <tr>
                    <th class="center" >Clasificación</th>
                    <th class="center">Producto</th>
                    <th class="center">Modelo</th>
                    <th class="center">Color</th>
                </tr>
            </thead>
            <tr>
                <td class="center" >
                    <div class="input-control select center full-size">
                        <select id="clasificacion">
                            <option value="0">Todas</option>
                            <?php foreach ($clasificaciones->result() as $clasificacion): ?>
                                <option value="<?= $clasificacion->IdClasificaciones; ?>"><?= $clasificacion->Letra; ?></option>
                            <?php endforeach; ?>
                            <option value="100">Sin Clasificar</option>
                        </select>
                    </div>
                </td>
                <td class="center bordered">
                    <div class="input-control select center full-size">
                        <select id="producto" onchange="ObtenerModelos()">
                            <option value="0">Todos</option>
                            <?php foreach ($productos->result() as $producto): ?>
                                <option value="<?= $producto->IdCProductos; ?>"><?= $producto->Nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </td>
                <td id="tdmodelo" class="center">
                    <div class="input-control select center full-size">
                        <select id="modelo" onchange="ObtenerColores()">
                            <option value="0">Cargando...</option>
                        </select>
                    </div>
                </td>
                <td id="tdcolor" class="center">
                    <div class="input-control select center full-size">
                        <select id="color">
                            <option value="0">Cargando...</option>
                        </select>
                    </div>
                </td>
            </tr> 
        </table>
        <br>
        <table class="table shadow">
            <thead>
                <tr>
                    <th colspan="3" class="center fg-darkBlue">TIPO DE REPORTE</th>
                </tr>
                <tr>
                    <th>Lista Detalle</th>
                    <th>Concentrado y Graficado Por:</th>
                </tr>
            </thead>
            <tr>
                <td class="center">
                    <i>Se mostrarán los productos a detalle</i><br>
                    <button id="" style="" class="button block-shadow-info text-shadow primary big-button" onclick="Detalle()"><span class="mif-arrow-right mif-ani-hover-horizontal"></span> Consultar</button>
                </td>
                <td class="center">
                    <div class="input-control select full-size">
                        <select id="concentradox">
                            <option value="Clasificacion(p.IdProductos)">Clasificación</option>
                            <option value="cp.IdCproductos">Producto</option>
                            <option value="m.IdModelos">Modelo</option>
                            <option value="co.IdColores">Color</option>
                        </select>
                    </div>
                    <br>
                    <button id="" style="" class="button block-shadow-warning text-shadow warning big-button" onclick="Concentrado()"><span class="mif-arrow-right mif-ani-hover-horizontal"></span> Consultar</button>
                </td>
            </tr>
        </table>

    </div>
    <div id="detalle" class="shadow"></div><br>
    <canvas id="myChart" width="300" height="100" class="shadow"></canvas>
    <script>
        function Grafica(etiquetas, valores) {
            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: etiquetas,
                    datasets: [{
                            label: "Productos",
                            data: valores,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                },
                options: {
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    }
                }
            });
        }
    </script>