<script>


</script>
<h1 class="light text-shadow">INVENTARIO CEDIS TARIMAS</h1><br>
<div class="progress large" id="pb2" data-parts="true" data-role="progress" data-value="0" data-colors="{&quot;bg-darkCobalt&quot;: 33, &quot;bg-darkCobalt&quot;: 66, &quot;bg-darkCobalt&quot;: 90, &quot;bg-darkCobalt&quot;: 100}"><div class="bar bg-green padding10" style="width: 100%;height: 35px;vertical-align: middle"><b class="fg-white" id="porc">0%</b></div></div>
<hr>
<div class="panel warning" data-role="panel" id="tarimas">
    <div class="heading">
        <span class="icon mif-stack fg-white bg-darkOrange"></span>
        <span class="title">TARIMAS</span>
    </div>
    <div class="content">
        <table class="table shadow" data-role="datatable">
            <thead>
                <tr>
                    <th class="align-center">Código</th>
                    <th class="center"># Productos</th>
                    <th class="center">Detalle</th>
                </tr>
            </thead>
            <?php foreach ($tarimas->result() as $tarima): ?>
                <?php
                $ci = &get_instance();
                $ci->load->model("modeloclasificador");
                $ca = &get_instance();
                $ca->load->model("modeloalmacenista");
                $codigotarima = $ci->modeloclasificador->CodigoBarrasTarimaTexto($tarima->IdTarimas);
                $pro = "No se encontró la tarima";
                $prods = $ca->modeloalmacenista->BuscarClaveTarimaExp($tarima->IdTarimas);
                if ($prods != "No se encontró la tarima") {
                    $pro = $prods->Productos;
                }
                ?>
                <tr>
                    <td class="center">
                        <?= $codigotarima ?>
                    </td>
                    <td class="center">
                        <?= $pro ?>
                    </td>
                    <td>
                        <a class="button success" href="ExpedienteTarima?tarima_id=<?= $tarima->IdTarimas ?>"">Detalle</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <br>
        <br>

    </div>
</div>
