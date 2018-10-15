<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el producto</b>
<div class="grid" >
    <div class="row cells<?= $productos->num_rows() ?>">
        <?php $cont = 1; ?>
        <?php foreach ($productos->result() as $producto): ?>
            <div class="cell" <?= $cont > 1 ? 'style="border-left: black solid 1px"' : '' ?>>
                <?php $cont++; ?>
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="CargarModelos(<?= $producto->IdCProductos; ?>)">
                    <div class="frame">
                        <img src="<?= base_url() ?>public/imagenes/<?= $producto->Imagen; ?>" height="190px;" width="190px;" title="<?= $producto->Nombre; ?>">
                        <br>
                        <h3><?= $producto->Nombre; ?></h3>
                        <p>
                            <?php
                            $ci = &get_instance();
                            $ci->load->model("modeloclasificador");
                            $npen = $ci->modeloclasificador->ProductosPendientesCproductos($dia, $horno, $producto->IdCProductos);
                            ?>
                            <?= $npen ?> prod. pendiente(s) de clasificación.
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<br><br>