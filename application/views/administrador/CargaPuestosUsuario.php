<script>
    function AgregarPuesto()
    {
        var puesto = $("#puestonuevo").val();
        var area = $("#areanuevo").val();
        var clave = $("#clave").val();

        $.post("AgregarPuesto", {"persona": "<?= $persona->IdPersonas ?>", "puesto": puesto, "area": area, "clave": clave}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El puesto se agregó correctamente");
                CargaPuestos();
            } else
            {
                MsjError("Ocurrió un error al agregar el puesto");
            }
        });
    }
    function EliminarPuesto(puesto)
    {
        $.post("EliminarPuesto", {"persona": "<?= $persona->IdPersonas ?>", "puesto": puesto}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El puesto se finalizó correctamente");
                CargaPuestos();
            } else
            {
                MsjError("Ocurrió un error al eliminar el puesto");
            }
        });
    }
</script>
<table class="table">
    <tr>
        <th>Puesto</th>
        <th>Área</th>
        <th>Clave</th>
    </tr>
    <tbody>
        <tr>
            <td class="center">
                <select style="height: 51px" id="puestonuevo" class="block-shadow-info full-size">
                    <?php
                    $cuantos = 0;
                    foreach ($puestostodos->result() as $puesto):
                        ?>
                        <?php
                        $ci = &get_instance();
                        $ci->load->model("modeloadministrador");
                        ?>
                        <?php
                        if ($ci->modeloadministrador->TienePuesto($persona->IdPersonas, $puesto->Nombre) == false) {
                            $cuantos++;
                            ?>
                            <option value="<?= $puesto->Nombre ?>"><?= $puesto->Nombre ?></option>
                            <?php
                        }
                        ?>
                        <?php
                    endforeach;
                    if ($cuantos == 0) {
                        echo '<option value = "0">El Usuario tiene todos los puestos disponibles</option>';
                    }
                    ?>
                </select>  
            </td>
            <td class="center">
                <select style="height: 51px" id="areanuevo" class="block-shadow-info full-size">
                    <?php
                    foreach ($areas->result() as $area):
                        ?>
                        <?php
                        $ci = &get_instance();
                        $ci->load->model("modeloadministrador");
                        ?>
                        <option value="<?= $area->IdAreas ?>"><?= $area->Nombre ?></option>
                        <?php
                    endforeach;
                    ?>
                </select>
            </td>
            <td class="center">
                <div class="input-control text"><input id="clave" type="text" placeholder="" style="height: 51px" class="block-shadow-info full-size"></div>
            </td>
            <td class="center">
                <?php
                if ($cuantos > 0) {
                    ?>
                    <button  id="botonagregarp" class="button block-shadow-info text-shadow primary big-button full-size" onclick="AgregarPuesto()">Agregar</button>
                <?php } ?>
            </td>
        </tr>
    </tbody>
</table>

<br><br>
<table class="table  bordered hovered">
    <thead>
        <tr>
            <th>Puesto</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Área</th>
            <th>Clave</th>
            <th>Acción</th>
        </tr>
    </thead>
    <?php foreach ($puestos->result() as $puesto): ?>
        <tr>
            <td><?= $puesto->Nombre ?></td>
            <td class="center"><?= $puesto->FechaInicio ?></td>
            <td class="center"><?= $puesto->FechaFin ?></td>
            <td class="center"><?= $puesto->Area ?></td>
            <td class="center"><?= $puesto->Clave ?></td>
            <td class="center">
                <?php
                if ($puesto->Activo == 1) {
                    ?>
                    <button class="button block-shadow-danger text-shadow danger big-button" onclick="EliminarPuesto(<?= $puesto->IdPuestos ?>)">Finalizar</button>
                <?php } ?>
            </td>
        </tr>    
    <?php endforeach; ?>
</table>