<h1> Bienvenido/a <?php echo $nombre ?> </h1>
<?php if ($perfiles->num_rows() == 0) { ?>
<h2>No tiene ningún perfil</h2>
<?php } else { ?>
<?php foreach ($perfiles->result() as $perfil): ?>
<h2><?= $perfil->Nombre ?></h2>
<?php endforeach; ?>
<?php } ?>
<p>
<a href="<?php echo base_url() ?>usuario/cerrar_sesion"> Cerrar sesión </a>
</p>
