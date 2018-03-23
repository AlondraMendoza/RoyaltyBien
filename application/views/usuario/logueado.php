
<h1> Bienvenido/a <?php echo $persona ?></h1><br>
<?php if ($perfiles->num_rows() == 0) { ?>
    <h2>No tiene ningún perfil</h2>
<?php } else { ?>
    <?php foreach ($perfiles->result() as $perfil): ?>
        <div class="panel" data-role="panel">
            <div class="heading fg-white bg-<?= $perfil->Color ?>">
                <span class="icon mif-<?= $perfil->Icono ?> fg-white bg-<?= $perfil->Color2 ?> "></span>
                <span class = "title fg-white"><?= $perfil->Nombre ?></span>
            </div>
            <div class="content" style="text-align: center;">
                <?php
                $ci = &get_instance();
                $ci->load->model("modelousuario");
                $npen = $ci->modelousuario->ObtenerMenu($perfil->IdPerfiles);
                ?>
                <?php if ($npen->num_rows() == 0) { ?>
                <?php } else { ?>
                    <br><br>
                    <?php foreach ($npen->result() as $m): ?>
                        <button title="<?= $m->Descripcion ?>" style="height: 100px;width: 170px" class ="button  bg-<?php echo $m->Color ?> fg-white" onclick = "window.location = '<?php echo base_url() ?><?= $m->Ruta ?>'">
                            <span style="font-size: 2.1em" class = "icon mif-<?php echo $m->Icono ?>"></span><hr style="background-color: #cccccc">
                            <span style="font-size: 1.2em"><?= $m->Nombre ?></span><br>
                        </button>
                    <?php endforeach; ?>
                    <br><br>
                <?php } ?>
            </div>
        </div><br>
    <?php endforeach; ?>
<?php } ?>