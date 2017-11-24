
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="<?= base_url() ?>public/css/metro.css" rel="stylesheet">
        <script src="<?= base_url() ?>public/js/jquery.js"></script>
        <link href="<?= base_url() ?>public/css/metro-icons.min.css" rel="stylesheet">
        <script src="<?= base_url() ?>public/js/metro.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {

            });
        </script>
        <title><?= $titulo ?></title>
    </head>
    <body>
        <div class="app-bar" data-role="appbar" style="background-color: #5a0303;padding-right: 20px;">
            <a class="app-bar-element" href="<?= base_url() ?>inicio/index"><img src="<?= base_url() ?>public/img/logo.png" style="height: 45px;width: 180px"></a>
            <ul class="app-bar-menu">
                <li><a href="">Inicio</a></li>
                <li><a href="">Cuenta</a></li>
                <li><a href="">Contacto</a></li>
                <ul class="app-bar-menu">
                    <li><a href="">Soporte</a></li>
                    <li>
                        <a href="" class="dropdown-toggle">Productos</a>
                        <ul class="d-menu" data-role="dropdown">
                            <li><a href="">Línea Residencial</a></li>
                            <li><a href="">Línea Comercial</a></li>
                            <li><a href="">Lavabos</a></li>
                            <li><a href="">Mingitorios</a></li>
                            <li class="divider"></li>
                            <li><a href="" class="dropdown-toggle">Otros productos</a>
                                <ul class="d-menu" data-role="dropdown">
                                    <li><a href="">Accesorios</a></li>
                                    <li><a href="">Producto especial 2</a></li>
                                    <li><a href="">Producto especial 3</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href=""></a></li>
                    <li><a href=""></a></li>
                </ul>
            </ul>
            <div class="app-bar-element place-right">
                <a class="fg-white" href="<?= base_url() ?>inicio/Login">Iniciar Sesión</a>
            </div>
        </div>
        <div class="contenido" style="padding-left: 30px;padding-right: 30px"><br>



