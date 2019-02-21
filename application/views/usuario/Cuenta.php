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
    function CambioContrasena()
    {
        $("#cambiocontrasena").toggle();
    }
    function CambioContra()
    {
        var contraactual = $("#contraactual").val();
        var contranueva = $("#contranueva").val();
        var contranueva2 = $("#contranueva2").val();
        if (contranueva == contranueva2)
        {
            $.post("CambioContra", {'contranueva': contranueva, 'contraactual': contraactual}, function (data) {
                if (data == "correcto")
                {
                    MsjCorrecto("La contraseña se guardó correctamente");
                } else if (data == "nocoincide") {
                    MsjError("La contraseña actual no es correcta");
                }
            });
        } else
        {
            MsjError("Las contraseñas no son iguales");
            // return 0;
        }



    }
</script>
<h1><b> EXPEDIENTE EMPLEADO</b></h1><br>
<center>
    <div class="panel warning" data-role="panel" style="overflow: hidden">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica</span>
        </div>
        <div class="content" >
            <table class="table">
                <tr>
                    <td class="center" rowspan="3" style="width: 30%">
                        <img onerror="this.src='<?= base_url() ?>public/imagenes/fotos/user.png'" class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/fotos/<?= $persona->IdPersonas ?>.jpg?v=<?= Date(DATE_RFC822, time()); ?>" height="250px;" width="250px;" title="<?= $persona->NombreCompleto; ?>">
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
                <tr>
                    <td>

                        <button  id="" class="button block-shadow-info text-shadow primary big-button full-size" onclick="CambioContrasena()">Cambiar Contraseña</button><br>
                        <br>
                        <div id="cambiocontrasena" style="display:none;">
                            <b>Contraseña actual</b>
                            <div class="input-control password full-size" data-role="input">
                                <input type="password" placeholder="Teclea la contraseña actual" name="contraactual" id='contraactual'>
                                <button class="button helper-button reveal"><span class="mif-looks"></span></button>
                            </div><br>
                            <b>Contraseña nueva</b>
                            <div class="input-control password full-size" data-role="input">
                                <input type="password" placeholder="Teclea la contraseña nueva" name="contranueva" id='contranueva'>
                                <button class="button helper-button reveal"><span class="mif-looks"></span></button>
                            </div><br>
                            <b>Contraseña nueva</b>
                            <div class="input-control password full-size" data-role="input">
                                <input type="password" placeholder="Teclea la contraseña nueva de nuevo" name="contranueva2" id='contranueva2'>
                                <button class="button helper-button reveal"><span class="mif-looks"></span></button>
                            </div>
                            <button  id="" class="button block-shadow-success text-shadow success big-button full-size" onclick="CambioContra()">Guardar</button><br>
                            <br><br><br>
                        </div>
                    </td>
                </tr>
            </table>
            <br><br><br>
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
                        <br><b>Estatus de Usuario</b><br>
                        <?php
                        if ($tieneusuario) {
                            if ($usuario->Activo == 1) {
                                echo "<b class='fg-green'>Activo</b><br><br>";
                            } else {
                                echo "<b class='fg-red'>Inactivo</b><br><br>";
                            }
                        } else {
                            ?>
                            <b class='fg-red'>No existe Usuario</b><br><br>
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