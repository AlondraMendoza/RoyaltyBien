<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el producto</b>
<div class="grid" >
    <div class="row cells1">
        <div class="cell" >
            <?php foreach ($productos->result() as $producto): ?>
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="CargarModelos(<?= $producto->IdCProductos; ?>)">
                    <div class="frame">
                        <img src="<?= base_url() ?>public/imagenes/<?= $producto->Imagen; ?>" height="190px;" width="190px;" title="<?= $producto->Nombre; ?>">        
                    </div>
                    <div class="image-overlay op-green">
                        <h2><?= $producto->Nombre; ?></h2>
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
            <?php endforeach; ?>
        </div>
    </div>   
</div>