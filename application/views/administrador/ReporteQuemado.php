<script>
    $(document).ready(function () {
        ObtenerModelos();
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
        var pb = $("#pb2").data('progress');
        pb.set(100);
        $("#grafica").html("");
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        var hornos = [];
        $(".hornos:checked").each(function ()
        {
            hornos.push(parseInt($(this).val()));
        });
        var hornoscadena = JSON.stringify(hornos);

        var productos = [];
        $(".productos:checked").each(function ()
        {
            productos.push(parseInt($(this).val()));
        });
        var productoscadena = JSON.stringify(productos);

        var modelos = [];
        $(".modelos:checked").each(function ()
        {
            modelos.push(parseInt($(this).val()));
        });
        var modeloscadena = JSON.stringify(modelos);

        var colores = [];
        $(".colores:checked").each(function ()
        {
            colores.push(parseInt($(this).val()));
        });
        var colorescadena = JSON.stringify(colores);
        $("#detalle").html("Cargando Información");
        $("#detalle").load("GenerarReporteQ", {"fechainicio": fechainicio, "fechafin": fechafin, "hornos": hornoscadena, "producto": productoscadena, "modelo": modeloscadena, "color": colorescadena})
    }
    function Concentrado()
    {
        var pb = $("#pb2").data('progress');
        pb.set(100);
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        var hornos = $("#hornos").val();
        var producto = $("#producto").val();
        var modelo = $("#modelo").val();
        var color = $("#color").val();
        var hornos = [];
        $(".hornos:checked").each(function ()
        {
            hornos.push(parseInt($(this).val()));
        });
        var hornoscadena = JSON.stringify(hornos);

        var productos = [];
        $(".productos:checked").each(function ()
        {
            productos.push(parseInt($(this).val()));
        });
        var productoscadena = JSON.stringify(productos);

        var modelos = [];
        $(".modelos:checked").each(function ()
        {
            modelos.push(parseInt($(this).val()));
        });
        var modeloscadena = JSON.stringify(modelos);

        var colores = [];
        $(".colores:checked").each(function ()
        {
            colores.push(parseInt($(this).val()));
        });
        var colorescadena = JSON.stringify(colores);
        var por = $("#concentradox").val();
        $("#detalle").html("Cargando Información");
        $("#detalle").load("GenerarConcentradoQ", {"fechainicio": fechainicio, "fechafin": fechafin, "hornos": hornoscadena, "producto": productoscadena, "modelo": modeloscadena, "color": colorescadena, "por": por});
    }
    function Paso(paso)
    {
        $("#paso1").fadeOut();
        $("#paso2").fadeOut();
        $("#paso3").fadeOut();
        $("#paso4").fadeOut();
        $("#paso5").fadeOut();
        $("#paso6").fadeOut();
        var pb = $("#pb2").data('progress');
        pb.set(paso * 15);
        $("#paso" + paso).fadeIn();
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
        <br>
        <center><button onclick="Paso(2)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button></center>
        <br>
        <br>

    </div>
</div>

<div class="panel warning" data-role="panel" id="paso2" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Paso 2: Hornos</span>
    </div>
    <div class="content">
        <br>
        <center>
            <?php foreach ($hornos->result() as $horno): ?>
                <label class="input-control checkbox">
                    <input type="checkbox" value="<?= $horno->IdHornos; ?>" name="hornos" class="hornos">
                    <span class="check"></span>
                    <span class="caption"><b>Horno <?= $horno->NHorno; ?></b></span>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php endforeach; ?>
            <br><br><br>
            <button onclick="Paso(1)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            <button onclick="Paso(3)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>

<div class="panel warning" data-role="panel" id="paso3" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Paso 3: Productos</span>
    </div>
    <div class="content">
        <br>
        <center>
            <?php foreach ($productos->result() as $producto): ?>
                <label class="input-control checkbox">
                    <input type="checkbox" value="<?= $producto->IdCProductos; ?>" name="productos" class="productos">
                    <span class="check"></span>
                    <span class="caption"><b><?= $producto->Nombre; ?></b></span>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php endforeach; ?>
            <br><br>
            <button onclick="Paso(2)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            <button onclick="Paso(4)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>

<div class="panel warning" data-role="panel" id="paso4" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Paso 4: Modelos</span>
    </div>
    <div class="content">
        <br>
        <center>
            <?php foreach ($modelos->result() as $modelo): ?>
                <label class="input-control checkbox">
                    <input type="checkbox" value="<?= $modelo->IdModelos; ?>" name="modelos" class="modelos">
                    <span class="check"></span>
                    <span class="caption"><b><?= $modelo->Nombre; ?></b></span>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php endforeach; ?>
            <br><br>
            <button onclick="Paso(3)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            <button onclick="Paso(5)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>

<div class="panel warning" data-role="panel" id="paso5" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">Paso 5: Colores</span>
    </div>
    <div class="content">
        <br>

        <center>
            <?php foreach ($colores->result() as $color): ?>
                <label class="input-control checkbox">
                    <input type="checkbox" value="<?= $color->IdColores; ?>" name="colores" class="colores">
                    <img style="border-radius: 100%" src="<?= base_url() ?>public/colores/<?= $color->Descripcion ?>" height="25px;" width="25px" title="<?= $color->Nombre ?>">
                    <span class="check" style="background-image: url(<?= base_url() ?>public/colores/<?= $color->Descripcion ?>)"></span>
                    <span class="caption"><b><?= $color->Nombre; ?></b></span>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php endforeach; ?>
            <br><br>
            <button onclick="Paso(4)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
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
                            <option value="p.HornosId">Hornos</option>
                            <option value="">Horneros</option>
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
        <center>
            <button onclick="Paso(5)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
        </center>
        <div id="detalle" class="shadow" ></div><br>
        <div id='grafica'>
            <canvas id="myChart" width="300" height="100" class="shadow"></canvas>
        </div>
        <script>
            function Grafica(etiquetas, valores) {

                $("#grafica").html('<canvas id="myChart" width="300" height="100" class="shadow"></canvas>');
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
    </div>
</div>


