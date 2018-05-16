<script>
    function AgregarPerfil()
    {
        var perfil = $("#perfilnuevo").val();
        $.post("AgregarPerfil", {"usuario": "<?= $usuario->IdUsuarios ?>", "perfil": perfil}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El perfil se agregó correctamente");
                CargaPerfiles();
            } else
            {
                MsjError("Ocurrió un error al agregar el perfil");
            }
        });
    }
    function EliminarPerfil(perfil)
    {
        $.post("EliminarPerfil", {"usuario": "<?= $usuario->IdUsuarios ?>", "perfil": perfil}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El perfil se eliminó correctamente");
                CargaPerfiles();
            } else
            {
                MsjError("Ocurrió un error al eliminar el perfil");
            }
        });
    }
</script>

<select style="width: 50%;height: 51px" id="perfilnuevo" class="block-shadow-info">
    <?php
    $cuantos = 0;
    foreach ($perfilestodos->result() as $perfil):
        ?>
        <?php
        $ci = &get_instance();
        $ci->load->model("modeloadministrador");
        ?>
        <?php
        if ($ci->modeloadministrador->TienePerfil($usuario->IdUsuarios, $perfil->IdPerfiles) == false) {
            $cuantos++;
            ?>
            <option value="<?= $perfil->IdPerfiles ?>"><?= $perfil->Nombre ?></option>
            <?php
        }
        ?>
        <?php
    endforeach;
    if ($cuantos == 0) {
        echo '<option value = "0">El Usuario tiene todos los perfiles disponibles</option>';
    }
    ?>
</select>
<?php
if ($cuantos > 0) {
    ?>
    <button  id="botonagregar" class="button block-shadow-info text-shadow primary big-button" onclick="AgregarPerfil()">Agregar</button>
<?php } ?><br><br>
<table class="table  bordered hovered">
    <thead>
        <tr>
            <th>Perfil</th>
            <th>Fecha Inicio</th>
            <th>Acción</th>
        </tr>
    </thead>
    <?php foreach ($perfiles->result() as $perfil): ?>
        <tr>
            <td><?= $perfil->Nombre ?></td>
            <td class="center"><?= $perfil->FechaInicio ?></td>
            <td class="center">
                <button class="button block-shadow-danger text-shadow danger big-button" onclick="EliminarPerfil(<?= $perfil->IdPerfilesUsuarios ?>)">Eliminar</button>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>