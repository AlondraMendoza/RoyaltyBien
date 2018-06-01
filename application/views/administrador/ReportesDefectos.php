<script>
    $(document).ready(function () {
        ObtenerModelos();
        //Grafica("Productos");
    });
    function Todos(clasedestino, claseorigen)
    {
        if ($("#" + claseorigen).prop('checked')) {
            $('.' + clasedestino).each(function () {
                this.checked = true;
            });
        } else {
            $('.' + clasedestino).each(function () {
                this.checked = false;
            });
        }

    }
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
    function DetalleSeleccionado(nombre)
    {
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        var clasificaciones = [];
        $(".clasificaciones:checked").each(function ()
        {
            clasificaciones.push(parseInt($(this).val()));
        });
        var clasificacionescadena = JSON.stringify(clasificaciones);
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
        var defectos = [];
        $(".defectos:checked").each(function ()
        {
            defectos.push(parseInt($(this).val()));
        });
        var defectoscadena = JSON.stringify(defectos);
        var por = $("#concentradox").val();
        $("#detalleseleccionado").fadeIn();
        $("#detalleseleccionado").html("<center style='font-size:1.2em'><b>Consultando Información...</b></center><br><br>");
        $("#detalleseleccionado").load("GenerarDetalleSeleccionadoDefectos", {"fechainicio": fechainicio, "fechafin": fechafin, "clasificacion": clasificacionescadena, "producto": productoscadena, "modelo": modeloscadena, "color": colorescadena, "defecto": defectoscadena, 'por': por, 'nombre': nombre});
    }
    function Detalle()
    {
        var pb = $("#pb2").data('progress');
        pb.set(100);
        $("#porc").html("100%");
        $("#grafica").html("");
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        var clasificaciones = [];
        $(".clasificaciones:checked").each(function ()
        {
            clasificaciones.push(parseInt($(this).val()));
        });
        var clasificacionescadena = JSON.stringify(clasificaciones);
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
        var defectos = [];
        $(".defectos:checked").each(function ()
        {
            defectos.push(parseInt($(this).val()));
        });
        var defectoscadena = JSON.stringify(defectos);
        $("#detalle").show();
        $("#detalle").html("<center style='font-size:1.2em'><b>Consultando Información...</b></center><br><br>");
        $("#grafica").html("");
        $("#detalleseleccionado").hide();
        $("#divtiporeporte").hide();
        $("#detalle").load("GenerarReporteDefectos", {"fechainicio": fechainicio, "fechafin": fechafin, "clasificacion": clasificacionescadena, "producto": productoscadena, "modelo": modeloscadena, "color": colorescadena, "defecto": defectoscadena});
    }
    function Concentrado()
    {
        var pb = $("#pb2").data('progress');
        pb.set(100);
        $("#porc").html("100%");
        var fechainicio = $("#fechainicio").val();
        var fechafin = $("#fechafin").val();
        var clasificacion = $("#clasificacion").val();
        var producto = $("#producto").val();
        var modelo = $("#modelo").val();
        var color = $("#color").val();
        var clasificaciones = [];
        $(".clasificaciones:checked").each(function ()
        {
            clasificaciones.push(parseInt($(this).val()));
        });
        var clasificacionescadena = JSON.stringify(clasificaciones);
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
        var defectos = [];
        $(".defectos:checked").each(function ()
        {
            defectos.push(parseInt($(this).val()));
        });
        var defectoscadena = JSON.stringify(defectos);
        var por = $("#concentradox").val();
        $("#detalle").show();
        $("#detalle").html("<center style='font-size:1.2em'><b>Consultando Información...</b></center><br><br>");
        $("#grafica").html("");
        $("#detalleseleccionado").hide();
        $("#divtiporeporte").hide();
        $("#detalle").load("GenerarConcentradoDefectos", {"fechainicio": fechainicio, "fechafin": fechafin, "clasificacion": clasificacionescadena, "producto": productoscadena, "modelo": modeloscadena, "color": colorescadena, "defecto": defectoscadena, "por": por});
    }
    function Paso(paso)
    {
        $("#paso1").hide();
        $("#paso2").hide();
        $("#paso3").hide();
        $("#paso4").hide();
        $("#paso5").hide();
        $("#paso6").hide();
        $("#paso7").hide();
        var pb = $("#pb2").data('progress');
        var porc = paso * 14;
        pb.set(porc);
        $("#porc").html(porc + "%");
        $("#paso" + paso).show();
    }
</script>
<h1 class="light text-shadow">REPORTE DEFECTOS</h1><br>
<div class="progress large" id="pb2" data-parts="true" data-role="progress" data-value="0" data-colors="{&quot;bg-darkCobalt&quot;: 33, &quot;bg-darkCobalt&quot;: 66, &quot;bg-darkCobalt&quot;: 90, &quot;bg-darkCobalt&quot;: 100}"><div class="bar bg-green padding10" style="width: 100%;height: 35px;vertical-align: middle"><b class="fg-white" id="porc">0%</b></div></div>
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
<div class="panel info" data-role="panel" id="paso2" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkBlue"></span>
        <span class="title">Paso 2: Clasificaciones</span>
    </div>
    <div class="content">
        <br>
        <center>
            <?php foreach ($clasificaciones->result() as $clasificacion): ?>
                <label class="input-control checkbox">
                    <input type="checkbox" value="<?= $clasificacion->IdClasificaciones; ?>" name="clasificaciones" class="clasificaciones">
                    <span class="check"></span>
                    <span class="caption"><b><?= $clasificacion->Letra; ?></b></span>
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php endforeach; ?>
            <label class="input-control checkbox">
                <input type="checkbox" value="todo" name="clasificacionestodo" id="clasificacionestodo" onclick="Todos('clasificaciones', 'clasificacionestodo')">
                <span class="check"></span>
                <span class="caption"><b class="fg-darkCobalt">Todas</b></span>
            </label>
            <br><br><br>
            <button onclick="Paso(1)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            <button onclick="Paso(3)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>
<div class="panel success" data-role="panel" id="paso3" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkGreen"></span>
        <span class="title">Paso 3: Productos</span>
    </div>
    <div class="content">
        <br>
        <center>
            <div class="flex-grid">
                <div class="row cell-auto-size">
                    <div class="cell">
                        <?php foreach ($productos->result() as $producto): ?>
                            <label class="input-control checkbox">
                                <input type="checkbox" value="<?= $producto->IdCProductos; ?>" name="productos" class="productos">
                                <span class="check"></span>
                                <span class="caption"><b><?= $producto->Nombre; ?></b></span>
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endforeach; ?>
                        <label class="input-control checkbox">
                            <input type="checkbox" value="todo" name="productostodo" id="productostodo" onclick="Todos('productos', 'productostodo')">
                            <span class="check"></span>
                            <span class="caption"><b class="fg-darkCobalt">Todos</b></span>
                        </label>
                    </div>
                </div>
            </div>
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
            <div class="flex-grid">
                <div class="row cell-auto-size">
                    <div class="cell">
                        <?php foreach ($modelos->result() as $modelo): ?>
                            <label class="input-control checkbox">
                                <input type="checkbox" value="<?= $modelo->IdModelos; ?>" name="modelos" class="modelos">
                                <span class="check"></span>
                                <span class="caption"><b><?= $modelo->Nombre; ?></b></span>
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endforeach; ?>
                        <label class="input-control checkbox">
                            <input type="checkbox" value="todo" name="modelostodo" id="modelostodo" onclick="Todos('modelos', 'modelostodo')">
                            <span class="check"></span>
                            <span class="caption"><b class="fg-darkCobalt">Todos</b></span>
                        </label>
                    </div>
                </div>
            </div>
            <br><br>
            <button onclick="Paso(3)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            <button onclick="Paso(5)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>

<div class="panel info" data-role="panel" id="paso5" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkBlue"></span>
        <span class="title">Paso 5: Colores</span>
    </div>
    <div class="content">
        <br>

        <center>
            <div class="flex-grid">
                <div class="row cell-auto-size">
                    <div class="cell">
                        <?php foreach ($colores->result() as $color): ?>
                            <label class="input-control checkbox">
                                <input type="checkbox" value="<?= $color->IdColores; ?>" name="colores" class="colores">
                                <img style="border-radius: 100%" src="<?= base_url() ?>public/colores/<?= $color->Descripcion ?>" height="25px;" width="25px" title="<?= $color->Nombre ?>">
                                <span class="check" style="background-image: url(<?= base_url() ?>public/colores/<?= $color->Descripcion ?>)"></span>
                                <span class="caption"><b><?= $color->Nombre; ?></b></span>
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endforeach; ?>
                        <label class="input-control checkbox">
                            <input type="checkbox" value="todo" name="colorestodo" id="colorestodo" onclick="Todos('colores', 'colorestodo')">
                            <span class="check"></span>
                            <span class="caption"><b class="fg-darkCobalt">Todos</b></span>
                        </label>
                    </div>
                </div>
            </div>
            <br><br>
            <button onclick="Paso(4)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            <button onclick="Paso(6)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>
<div class="panel info" data-role="panel" id="paso6" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkBlue"></span>
        <span class="title">Paso 6: Defectos</span>
    </div>
    <div class="content">
        <br>
        <center>
            <div class="flex-grid">
                <div class="row cell-auto-size">
                    <div class="cell">
                        <center>
                            <label class="input-control checkbox">
                                <input type="checkbox" value="todo" name="defectostodo" id="defectostodo" onclick="Todos('defectos', 'defectostodo')">
                                <span class="check"></span>
                                <span class="caption"><b class="fg-darkCobalt">Marcar todos los defectos</b></span>
                            </label>
                        </center>
                        <table class="table shadow" data-role="datatable">
                            <thead>
                                <tr>
                                    <th>Seleccionar</th>
                                    <th>Defecto</th>
                                    <th>Categoría</th>
                            </thead>
                            <?php foreach ($defectos->result() as $defecto): ?>
                                <?php
                                $ci = &get_instance();
                                $ci->load->model("modeloadministrador");
                                $categ = $ci->modeloadministrador->CategoriaDefecto($defecto->IdDefectos);
                                ?>
                                <tr>
                                    <td>
                                        <label class = "input-control checkbox">
                                            <input type = "checkbox" value = "<?= $defecto->IdDefectos; ?>" name = "defectos" class = "defectos">
                                            <span class = "check"></span>
                                            <span class = "caption"><b></b></span>
                                        </label>
                                    </td>
                                    <td>
                                        <?= $defecto->Nombre; ?>
                                    </td>
                                    <td><?= $categ->row()->Nombre ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>
            </div>
            <br><br>
            <button onclick="Paso(5)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            <button onclick="Paso(7)" class="button block-shadow-info text-shadow primary big-button">Siguiente</button>
        </center>
    </div>
</div>

<div class="panel success" data-role="panel" id="paso7" style="display: none">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkGreen"></span>
        <span class="title">Paso 7: Generación de Reporte</span>
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
                                <option value="d.IdDefectos">Defecto</option>
                            </select>
                        </div>
                        <br>
                        <button id="" style="" class="button block-shadow-warning text-shadow warning big-button" onclick="Concentrado()"><span class="mif-arrow-right mif-ani-hover-horizontal"></span> Consultar</button>
                    </td>
                </tr>
            </table>
            <center>
                <button onclick="Paso(6)" class="button block-shadow-alert text-shadow alert big-button">Atrás</button>
            </center>
        </div>
        <div id="detalle" class="shadow" ></div><br><div id="texto"></div>
        <div id='grafica'>
            <canvas id="myChart" width="300" height="100" class=""></canvas>
        </div>
        <div id="detalleseleccionado"> </div>
        <script>

            function Grafica(etiquetas, valores) {

                $("#grafica").html('<canvas id="myChart" width="300" height="100" class=""></canvas>');
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
//                    onClick: function(c, i) {
//                    e = i[0];
//                    console.log(e._index);
//                    var x_value = this.data.labels[e._index];
//                    var y_value = this.data.datasets[0].data[e._index];
//                    console.log(x_value);
//                    console.log(y_value);
                        //                    }
                    }
                });
                document.getElementById("myChart").onclick = function (evt) {
                    var activePoints = myChart.getElementAtEvent(evt);
                    // var theElement = myChart.config.data.datasets[activePoints[0]._datasetIndex].data[activePoints[0]._index];

                    //alert(etiquetas[activePoints[0]._index]);
                    //alert(valores[activePoints[0]._index]);
                    DetalleSeleccionado(etiquetas[activePoints[0]._index]);
//                    console.log(theElement);
                    //                    console.log(myChart.config.data.datakeys[activePoints[0]._index]);
                    // document.getElementById("texto").innerText = myChart.config.da [activePoints[0]._index];
                    //                    console.log(myChart.config.type);
                }

            }</script>
    </div>
</div>
