<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el color</b>
<div class="grid" >
    <div class="row cells<?= $colores->num_rows() + 1 ?>">
        <div class="cell">
            <div class="image-container rounded bordered" style="width: 200px;height: 200px">
                <div class="frame center" style="vertical-align: middle">
                    <span onclick="CargarModelos(<?= $cprod ?>)" style="font-size: 5em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de Modelos"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <h3>Regresar</h3>
                    <p>A Selección de Modelo</p>
                </div>
            </div>
        </div>

        <?php foreach ($colores->result() as $color): ?>
            <div class="cell" style="border-left: black solid 1px">
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="TablaProductos(<?= $color->CProductosId . ',' . $color->ModelosId . ',' . $color->IdColores ?>)">
                    <div class="frame">
                        <img src="<?= base_url() ?>public/colores/<?= $color->Descripcion ?>" height="190px;" width="190px;" title="<?= $color->Nombre ?>">        <br>
                        <h3><?= $color->Nombre ?></h3>
                        <p>
                            <?php
                            $ci = &get_instance();
                            $ci->load->model("modeloclasificador");
                            $npen = $ci->modeloclasificador->ProductosPendientesColores($dia, $horno, $color->CProductosId, $color->ModelosId, $color->IdColores);
                            ?>
                            <?= $npen ?> prod. pendiente(s) de clasificación.
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
