<script>
    $(document).ready(function () {
        CargaPerfiles();
        CargaPuestos();
    });
    function CargaPerfiles()
    {
        $("#PerfilesUsuario").load("CargaPerfilesUsuario", {"usuario": "<?= $usuario->IdUsuarios ?>"});
    }
    function CargaPuestos()
    {
        $("#PuestosUsuario").load("CargaPuestosUsuario", {"usuario": "<?= $usuario->IdUsuarios ?>"});
    }
</script>
<h1><b> EXPEDIENTE USUARIO</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica</span>
        </div>
        <div class="content">
            <table class="table">
                <tr>
                    <td class="center" rowspan="2" style="width: 30%">
                        <img class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/fotos/<?= $usuario->IdUsuarios ?>.jpg" height="250px;" width="250px;" title="<?= $usuario->NombreCompleto; ?>">        
                        <br><br>
                    </td> 
                </tr>
                <tr>
                    <td>
                        <b>Nombre:</b><br><br><?= $usuario->NombrePersona; ?>
                        <hr>
                        <b>Apellido Paterno:</b><br><br><?= $usuario->APaterno; ?>
                        <hr>
                        <b>Apellido Materno:</b><br><br><?= $usuario->AMaterno; ?>
                        <hr>
                    </td>
                </tr>
                <br><br>
            </table>
        </div>
    </div>
    <br>
    <div class="panel success" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkGreen"></span>
            <span class="title">Estados actuales del usuario</span>
        </div>
        <div class="content" id="">
            <table class="table">
                <tr>
                    <td class="center">
                        <b>Estatus de Usuario</b>
                        <br>
                        <?php
                        if ($usuario->Activo == 1) {
                            echo "Activo";
                        } else {
                            echo "Inactivo";
                        }
                        ?>
                    </td>
                    <td class="center">
                        <b>Último Puesto</b>
                        <br>
                        <?= $ultimopuesto->Nombre ?>
                    </td>
                    <td class="center">
                        <b>Última Área</b>
                        <br>
                        <?= $ultimopuesto->Area ?>
                    </td>
                    <td class="center">
                        <b>Último Perfil asignado</b>
                        <br>
                        <?= $ultimoperfil->Nombre ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <div>
        <center>
            <div class="panel primary" data-role="panel">
                <div class="heading">
                    <span class="icon mif-stack fg-white bg-darkBlue"></span>
                    <span class="title">Detalle de movimientos</span>
                </div>
                <div class="content" id="">
                    <br>
                    <div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
                        <ul class="tabs">
                            <li class="active"><a href="#puestos">Puestos</a></li>
                            <li><a href="#perfiles">Perfiles</a></li>
                        </ul>
                        <div class="frames">
                            <div class="frame" id="puestos">
                                <div id="PuestosUsuario"></div>
                            </div>
                        </div>
                        <div class="frames">
                            <div class="frame" id="perfiles">
                                <div id="PerfilesUsuario"></div>
                            </div>
                        </div>
                    </div>
                </div>
        </center>
    </div>
</center><br><br><br>