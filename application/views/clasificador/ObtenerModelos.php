<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el modelo</b>
<div class="grid" >
    <div class="row cells1">
        <div class="cell" >
            <span onclick="CargarProductos()" style="font-size: 5em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de productos"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php foreach ($modelos->result() as $modelo): ?>
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="CargarColores(<?= $modelo->ModelosId . ',' . $modelo->CProductosId ?>)">
                    <div class="frame">
                        <?php
                        $img = &get_instance();
                        $img->load->model("modeloclasificador");
                        ?>
                        <img src="<?= base_url() ?>public/imagenes/<?= $img->modeloclasificador->ImagenProductoModelo($modelo->CProductosId, $modelo->ModelosId) ?>" height="190px;" width="190px;" title="<?= $modelo->Nombre ?>">        <br>
                        <h2><?= $modelo->Nombre ?></h2>
                        <p>
                            <?php
                            $ci = &get_instance();
                            $ci->load->model("modeloclasificador");
                            $npen = $ci->modeloclasificador->ProductosPendientesModelos($dia, $horno, $modelo->CProductosId, $modelo->ModelosId);
                            ?>
                            <?= $npen ?> prod. pendiente(s) de clasificación.
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
