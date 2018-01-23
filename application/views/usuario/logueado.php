<h1> Bienvenido/a <?php echo $nombre ?> </h1>
<?php if ($perfiles->num_rows() == 0) { ?>
<h2>No tiene ningún perfil</h2>
<?php } else { ?>
<?php foreach ($perfiles->result() as $perfil): ?>
<h2><?= $perfil->Nombre ?></h2>
<?php
$ci = &get_instance();
$ci->load->model("modelousuario");
$npen = $ci->modelousuario->ObtenerMenu($perfil->IdPerfiles);
?>
<?php if ($npen->num_rows() == 0) { ?>
<?php } else { ?>
<div class = "panel">
            <div class = "heading bg-lightBlue " >
                <span class = "icon mif-home bg-darkBlue "></span>
                <span class = "title fg-black"><?php echo "Funciones" ?></span>
            </div>
            <div class = "content" style="text-align: center;">
                <br><br>
<?php foreach ($npen->result() as $m): ?>
        
                    <button class = "command-button icon-left bg-<?php echo $m->Color ?> fg-white" onclick = "window.location = 'BitacoraCorrecciones.html'">
                        <span class = "icon mif-<?php echo $m->Icono ?>"></span>
                        <?php echo $m->Nombre ?>
                        <small><?php echo $m->Descripcion ?></small>
                    </button>
                
            
<?php endforeach; ?>
                <br><br>
                <br><br>
                </div>
        </div>
<?php } ?>
<?php
$ci = &get_instance();
$ci->load->model("modelousuario");
$nfun = $ci->modelousuario->ObtenerReportes($perfil->IdPerfiles);
?>
<?php if ($nfun->num_rows() == 0) { ?>
<?php } else { ?>
<div class = "panel">
            <div class = "heading bg-lightGray " >
                <span class = "icon mif-chart-line bg-darkGray "></span>
                <span class = "title fg-black"><?php echo "Reportes" ?></span>
            </div>
            <div class = "content" style="text-align: center;">
                <br><br>
<?php foreach ($nfun->result() as $r): ?>
                    <button class = "command-button icon-left bg-<?php echo $r->Color ?> fg-white" onclick = "window.location = 'BitacoraCorrecciones.html'">
                        <span class = "icon mif-<?php echo $r->Icono ?>"></span>
                        <?php echo $r->Nombre ?>
                        <small><?php echo $r->Descripcion ?></small>
                    </button>
                <?php endforeach; ?>
                <br><br>
                <br><br>
            </div>
        </div>
<?php } ?>
<?php endforeach; ?>
<?php } ?>
<p>
<a href="<?php echo base_url() ?>usuario/cerrar_sesion"> Cerrar sesión </a>
</p>