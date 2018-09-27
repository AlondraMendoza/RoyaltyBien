<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el modelo:</b>
<div class="grid" >
    <div data-role="group" data-group-type="one-state">
       <?php if ($modelos->num_rows() == 0) { ?>
        <label>No hay modelos</label>
        <?php } else { ?>
        <?php foreach ($modelos->result() as $modelo ): ?>
        <?php
        $ci = &get_instance();
        $ci->load->model("modelocedis"); 
        //$npen = $ci->modelocapturista->ListarModelos($prod->IdCProductos);?>
        <button id="modelo<?= $modelo->IdModelos ?>" class="button" style='width: 210px; height: 210px;' onclick="AbrirColores(<?= $modelo->IdModelos?>)">
        <input type="image" src="<?php echo base_url() ?>public/imagenes/<?= $modelo->Imagen ?>" height="190px;" width="190px;" title="<?= $modelo->Nombre ?>"/><b>
        <?= $modelo->Nombre ?></b></button>
        <?php endforeach; ?>
        <?php } ?>
    </div>   
</div>


