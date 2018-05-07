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
    
    function GuardarP(){
        var nombre = $("#nombre").val();
        //falta imagen
        $.post("NuevoProducto", {"nombre": nombre}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El producto se guardó correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al guardar el producto");
            }
        });
    }
    
    
</script>


