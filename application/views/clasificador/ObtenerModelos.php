<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el modelo</b>
<div class="grid" >
    <div class="row cells<?= $modelos->num_rows() + 1 ?>">
        <div class="cell">
            <div class="image-container rounded bordered" style="width: 200px;height: 200px">
                <div class="frame center" style="vertical-align: middle">
                    <span onclick="CargarProductos()" style="font-size: 5em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de productos"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <h3>Regresar</h3>
                    <p>A Selección de Producto</p>
                </div>
            </div>
        </div>
        <?php foreach ($modelos->result() as $modelo): ?>
            <div class="cell" style="border-left: black solid 1px">
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="CargarColores(<?= $modelo->ModelosId . ',' . $modelo->CProductosId ?>)">
                    <div class="frame">
                        <?php
                        $img = &get_instance();
                        $img->load->model("modeloclasificador");
                        ?>
                        <img src="<?= base_url() ?>public/imagenes/<?= $img->modeloclasificador->ImagenProductoModelo($modelo->CProductosId, $modelo->ModelosId) ?>" height="190px;" width="190px;" title="<?= $modelo->Nombre ?>">        <br>
                        <h3><?= $modelo->Nombre ?></h3>
                        <p>
                            <?php
                            $ci = &get_instance();
                            $ci->load->model("modeloclasificador");
                            $npen = $ci->modeloclasificador->ProductosPendientesModelos($dia, $horno, $modelo->CProductosId, $modelo->ModelosId);
                            ?>
                            <?= $npen ?> prod. pendiente(s) de clasificación.
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
