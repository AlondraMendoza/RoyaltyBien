<h1><b> Productos </b></h1><br>
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
                        <th>Nombre Productos</th>
                        <th>Imagén</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos->result() as $prod): ?>
                        <tr>
                            <td class="bordered">
                                <?= $prod->Nombre ?>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                                <img class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/<?= $prod->Imagen ?>" height="100px;" width="100px;">
<!--                                <form enctype="multipart/form-data" action="uploader.php" method="POST">
                                <input name="uploadedfile" type="file" />
                                <input type="submit" value="Subir archivo" />
                                </form>-->
                                <div id="formulario_imagenes">
                                <form action="SubirImagenProducto" enctype="multipart/form-data" method="post">
                                <div class="input-control file " data-role="input">
                                <input type="file" name="userfile" />
                                <input type="hidden" name="productoid" value="<?= $prod->IdCProductos ?>">
                                <button class="button"><span class="mif-folder"></span></button>
                                </div>
                                <input type="submit" class="button primary" value="Subir imagen"/>
                                </center>
                                </form>
                                </div>
                            </td> 
                            <?php if ($prod->Activo==1){ ?>
                            <td class="center">
                                <a class="button block-shadow-info text-shadow primary" href="DetalleProd?producto=<?= $prod->IdCProductos ?>">Ver Detalle</a>
                                <a class="button block-shadow-info text-shadow alert" onclick="Desactivar(<?= $prod->IdCProductos?>)">Desactivar</a>
                            </td>
                            <?php } else {?>
                            <td class="center">
                                <a class="button block-shadow-info text-shadow success" onclick="Activar(<?= $prod->IdCProductos?>)">Activar</a>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <td class="bordered">
                                <div class="input-control text" >
                                    <input type="text" id="nombre" placeholder="Nombre producto">
                                </div>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                                <img class="block-shadow-warning" src="<?= base_url() ?>public/imagenes/SinImagen.png" height="100px;" width="100px;">
                            </td> 
                            <td class="center">
                                <a class="button block-shadow-info text-shadow success" onclick="GuardarP()">Guardar Producto</a>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</center>
<script>
    function Desactivar(producto){
        $.post("DesactivarProducto", {"producto": producto}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El producto se desactivo correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al desactivar el producto");
            }
        });
    }
    
    function Activar(producto){
        $.post("ActivarProducto", {"producto": producto}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El producto se activo correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al activar el producto");
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
