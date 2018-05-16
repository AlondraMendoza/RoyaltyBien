<script>
    $(document).ready(function () {
        /*<?php if ($tieneusuario) { ?>*/
        CargaPerfiles();
        /*<?php } ?>*/
        CargaPuestos();
    });
    /*<?php if ($tieneusuario) { ?>*/
    function CargaPerfiles()
    {
        $("#PerfilesUsuario").load("CargaPerfilesUsuario", {"usuario": "<?= $usuario->IdUsuarios ?>"});
    }
    /*<?php } ?>*/
    function CargaPuestos()
    {
        $("#PuestosUsuario").load("CargaPuestosUsuario", {"persona": "<?= $persona->IdPersonas ?>"});
    }
</script>
<h1><b> EXPEDIENTE EMPLEADO</b></h1><br>
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

                        <img onerror="this.src='<?= base_url() ?>public/imagenes/fotos/1.jpg'" class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/fotos/<?= $persona->IdPersonas ?>.jpg" height="250px;" width="250px;" title="<?= $persona->NombreCompleto; ?>">        

                        <br><br>
                    </td> 
                </tr>
                <tr>
                    <td>
                        <b>Nombre:</b><br><br><?= $persona->NombrePersona; ?>
                        <hr>
                        <b>Apellido Paterno:</b><br><br><?= $persona->APaterno; ?>
                        <hr>
                        <b>Apellido Materno:</b><br><br><?= $persona->AMaterno; ?>
                        <hr>
                        <b>Usuario:</b><br><br>
                        <?php
                        if ($tieneusuario) {
                            echo $usuario->Nombre;
                        } else {
                            echo "No Existe Usuario";
                        }
                        ?>
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
                        if ($tieneusuario) {
                            if ($usuario->Activo == 1) {
                                echo "Activo";
                                ?>
                                <form action = "CancelarUsuario">
                                    <input type = "submit" class = "danger" value = "Desactivar Usuario">
                                    <input type = "hidden" name = "persona_id" value = "<?= $persona->IdPersonas ?>">
                                </form>
                                <?php
                            } else {
                                echo "Inactivo";
                            }
                        } else {
                            ?>
                            No existe Usuario
                            <form action="CrearUsuario">
                                <input type="submit" class="warning" value="Crear Usuario">
                                <input type="hidden" name="persona_id" value="<?= $persona->IdPersonas ?>">
                            </form>

                        <?php } ?>
                    </td>
                    <td class="center">
                        <b>Último Puesto</b>
                        <br>
                        <?php if ($ultimopuesto != null) { ?>
                            <?= $ultimopuesto->Nombre ?>
                        <?php } ?>
                    </td>
                    <td class="center">
                        <b>Última Área</b>
                        <br>
                        <?php if ($ultimopuesto != null) { ?>
                            <?= $ultimopuesto->Area ?>
                        <?php } ?>
                    </td>
                    <td class="center">
                        <b>Último Perfil asignado</b>
                        <br>
                        <?php if ($ultimoperfil != null) { ?>
                            <?php if ($tieneusuario) { ?>
                                <?= $ultimoperfil->Nombre ?>
                            <?php } else { ?>
                                <?= $ultimoperfil ?>
                            <?php } ?>
                        <?php } ?>
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