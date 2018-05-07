<h1><b> Detalle Productos y modelos</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica</span>
        </div>
        <div class="content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Modelos</th>
                        <th>Imagén</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <label id="prod" style="display:none;"><?=$producto ?></label>
                    <?php foreach ($modelos->result() as $mod): ?>
                        <tr>
                            <td class="bordered">
                                <?= $mod->Nombre ?>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                                <img class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/<?= $mod->Imagen ?>" height="100px;" width="100px;">
                                <form enctype="multipart/form-data" action="uploader.php" method="POST">
                                <input name="uploadedfile" type="file" />
                                <input type="submit" value="Subir archivo" />
                                </form>
                            </td>
                            <?php if ($mod->Activocm==1){ ?>
                            <td class="center">
                                <a class="button block-shadow-info text-shadow primary" href="DetalleMod?modelo=<?= $mod->IdModelos ?>">Ver Colores</a>
                                <a class="button block-shadow-info text-shadow alert" onclick="Desactivar(<?= $mod->codigo ?>)">Desactivar</a>
                            </td>
                            <?php } else {?>
                            <td class="center">
                                <a class="button block-shadow-info text-shadow success" onclick="Activar(<?= $mod->codigo?>)">Activar</a>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <label id="mod" style="display:none;"></label>
                            <td>
                                <b style="font-size: 1.3em" class="fg-darkEmerald"> Modelos existentes:</b><br>
                                <div class="input-control select">   
                                    <select id="todo">                                    
                                        <option value="0">Selecciona el modelo</option>
                                            <?php foreach ($todos->result() as $todo): ?>
                                            <option value="<?= $todo->IdModelos ?>"><?= $todo->Nombre ; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                                <img class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/SinImagen.png" height="100px;" width="100px;">
                                <form enctype="multipart/form-data" action="uploader.php" method="POST">
                                <input name="uploadedfile" type="file" />
                                <input type="submit" value="Subir archivo" />
                                </form>
                            </td> 
                            <td class="center">
                                <a class="button block-shadow-info text-shadow success" onclick="GuardarM()">Guardar Modelo</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="bordered">
                                <b style="font-size: 1.3em" class="fg-darkEmerald"> Nuevo modelo:</b><br>
                                <div class="input-control text" >
                                    <input type="text" id="nombreM" placeholder="Nombre modelo">
                                </div>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                                <img class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/SinImagen.png" height="100px;" width="100px;">
                                <form enctype="multipart/form-data" action="uploader.php" method="POST">
                                <input name="uploadedfile" type="file" />
                                <input type="submit" value="Subir archivo" />
                                </form>
                            </td> 
                            <td class="center">
                                <a class="button block-shadow-info text-shadow success" onclick="GuardarN()">Guardar Modelo</a>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</center>
<script>
    function Desactivar(codigo){
        $.post("DesactivarModelo", {"codigo": codigo}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El modelo se desactivo correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al desactivar el modelo");
            }
        });
    }
    
    function Activar(codigo){
        $.post("ActivarModelo", {"codigo": codigo}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El modelo se activo correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al activar el modelo");
            }
        });
    }
    
    function Notificacion(titulo, texto, icono, color)
    {
        $.Notify({
            caption: titulo,
            content: texto,
            icon: "<span class='mif-" + icono + "'></span>",
            type: color
        });
    }
    
    function GuardarM(){
        var nombre = $("#todo").val();
        var producto =  $("#prod").text();
        if(nombre ==0){
            Notificacion("Error", "Selecciona el modelo antes de continuar", "cancel", "alert");
            return(0);
        }
        //falta imagen
        $.post("SeleccionModelo", {"nombre": nombre, "producto": producto}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El modelo se guardó correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al guardar el modelo");
            }
        });
    }
    
    function GuardarN(){
        var nombre = $("#nombreM").val();
        var producto =  $("#prod").text();
        //falta imagen
        $.post("NuevoModelo", {"nombre": nombre, "producto": producto}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El modelo se guardó correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al guardar el modelo");
            }
        });
    }
    
    
</script>


