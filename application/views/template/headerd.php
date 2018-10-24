<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?= base_url() ?>public/css/metro.css" rel="stylesheet">
        <script src="<?= base_url() ?>public/js/jquery.js"></script>
        <link href="<?= base_url() ?>public/css/metro-icons.min.css" rel="stylesheet">
        <script src="<?= base_url() ?>public/js/metro.js"></script>
        <script src="<?= base_url() ?>public/js/chart.min.js"></script>
        <script src="<?= base_url() ?>public/js/Funciones.js"></script>
        <script src="<?= base_url() ?>public/js/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>public/js/jquery.PrintArea.js" type="text/JavaScript" language="javascript"></script>
        <title><?= $titulo ?></title>
    </head>
    <body>
        <div class="app-bar" data-role="appbar" style="position: fixed;z-index: 999999;background-color: #5a0303;padding-right: 20px;">
            <a class="app-bar-element" href="<?= base_url() ?>inicio/index"><img src="<?= base_url() ?>public/img/logo.png" style="height: 45px;width: 180px"></a>
            <ul class="app-bar-menu">
                <li><a href="<?php echo base_url() ?>usuario/logueado">Inicio</a></li>
                <li><a href="<?php echo base_url() ?>usuario/Cuenta">Cuenta</a></li>
                <ul class="app-bar-menu">
                    <li><a href="<?= base_url() ?>usuario/Soporte">Soporte</a></li>
                </ul>
            </ul>
            <div class="app-bar-element place-right">
                <a class="dropdown-toggle fg-white"> <img onerror="this.src='<?= base_url() ?>public/imagenes/fotos/user.png'" src="<?= base_url(); ?>public/imagenes/fotos/<?= IdPersona(); ?>.jpg" style="border-radius: 50%;width:33px;height: 33px"> &nbsp;&nbsp;<?= $this->session->userdata('nombre'); ?></a>
                <div class="app-bar-drop-container bg-white fg-dark place-right" data-role="dropdown" data-no-close="true" style="z-index: 99999">
                    <div class="padding10" style="width: 200px;">
                        <center>
                            <img onerror="this.src='<?= base_url() ?>public/imagenes/fotos/user.png'" src="<?= base_url() ?>public/imagenes/fotos/<?= IdUsuario() ?>.jpg" height="150px;" width="150px;" title="<?= NombreUsuario() ?>">
                            <br>
                            <h5 id="nomper"><b><?= $this->session->userdata('persona'); ?></b></h5>
                            <?php
                            $id = $this->session->userdata('id');
                            ?>
                            <?php
                            $ci = &get_instance();
                            $ci->load->model("modelousuario");
                            $perfiles = $ci->modelousuario->ObtenerPerfiles($id);
                            ?>
                        </center>
                        <ul class="simple-list blue-bullet">

                            <?php foreach ($perfiles->result() as $perfil): ?>
                                <li class="small"><?= $perfil->Nombre ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <center>
                            <button class="text-shadow block-shadow-success button success" onclick="window.location = '<?php echo base_url() ?>usuario/cerrar_sesion'">Salir</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <div style="z-index: 9999;width: 40px;height: 40px;position: fixed;right:0px;top:50px;background-color: #5a0303;color:white;text-align: center"><a id="menu" href="<?php echo base_url() ?>usuario/logueado"><i class="mif-home mif-2x fg-white" ></i></a></div>
        <div class="contenido" style="padding-left: 30px;padding-right: 30px"><br><br><br><br>