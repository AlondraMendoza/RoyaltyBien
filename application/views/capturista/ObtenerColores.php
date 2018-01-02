<b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el color:</b>
<div class="grid" >
    <div data-role="group" data-group-type="one-state">
       <?php if ($colores->num_rows() == 0) { ?>
        <label>No hay colores</label>
        <?php } else { ?>
        <?php foreach ($colores->result() as $col ): ?>
        <?php
        $ci = &get_instance();
        $ci->load->model("modelocapturista"); 
        //$npen = $ci->modelocapturista->ListarModelos($prod->IdCProductos);?>
        <button class="button" id="color<?= $col->IdColores?>" style='width: 210px; height: 210px;' onclick="VerOtros(<?= $col->IdColores?>)">
        <input type="image" src="<?php echo base_url() ?>public/colores/<?= $col->Descripcion ?>" height="190px;" width="190px;" title="<?= $col->Nombre ?>"/><b>
        <?= $col->Nombre ?></b></button>
        <?php endforeach; ?>
        <?php } ?>
    </div>   
</div>


