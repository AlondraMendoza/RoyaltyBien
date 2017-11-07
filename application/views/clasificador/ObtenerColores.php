<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el color</b>
<div class="grid" >
    <div class="row cells1">
        <div class="cell" >
            <span onclick="CargarModelos(<?= $cprod ?>)" style="font-size: 5em" class="mif-undo mif-ani-hover-spanner mif-ani-slow" title="Regresar a lista de Modelos"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php foreach ($colores->result() as $color): ?>
                <div class="image-container rounded bordered" style="width: 200px;height: 200px" onclick="TablaProductos(<?= $color->CProductosId . ',' . $color->ModelosId . ',' . $color->IdColores ?>)">
                    <div class="frame">
                        <img src="<?= base_url() ?>public/colores/<?= $color->Descripcion ?>" height="190px;" width="190px;" title="<?= $color->Nombre ?>">        
                    </div>
                    <div class="image-overlay op-orange">
                        <h2><?= $color->Nombre ?></h2>
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
            <?php endforeach; ?>    
        </div>
    </div>
</div>
