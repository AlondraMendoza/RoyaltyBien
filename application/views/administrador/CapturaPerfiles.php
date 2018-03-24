<h1><b> Configuración de accesos</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica</span>
        </div>
        <div class="content">
            <table class="table  ">
                <thead>
                <th>Usuarios</th>
                <th colspan="<?= $perfiles->num_rows() ?>">Perfiles</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="bordered">
                            <div id="" class="input-control select full-size" style="height: 40px;">
                                <select onselect= "CargarProductos()" id="hornos" onchange="CargarPerfiles()">
                                    <?php foreach ($usuarios->result() as $usuario): ?>
                                        <option value="<?= $usuario->IdUsuarios ?>"><?= $usuario->NombreCompleto ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </td>
                        <?php foreach ($perfiles->result() as $perfil): ?>
                            <th>
                                <?= $perfil->Nombre; ?>
                            </th>
                        <?php endforeach; ?>

                    </tr>
                    <tr class="bordered">
                        <?php foreach ($perfiles->result() as $perfil): ?>
                            <td class="center bordered">
                                <label class="input-control checkbox">
                                    <input type="checkbox" value="<?= $perfil->IdPerfiles; ?>" name="perfiles" class="perfiles">
                                    <span class="check"></span>
                                    <span class="caption"><b></b></span>
                                </label>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>