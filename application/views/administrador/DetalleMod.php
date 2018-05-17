<h1><b> Detalle Productos, modelos y colores</b></h1><br>
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
                        <th>Colores</th>
                        <th>Imagén</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <label id="mod" style="display:none;"><?=$modelo ?></label>
                    <?php foreach ($colores->result() as $col): ?>
                        <tr>
                            <td class="bordered">
                                <?= $col->Nombre ?>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                                <img class="block-shadow-warning" src="<?= base_url() ?>public/colores/<?= $col->Descripcion ?>" height="100px;" width="100px;">
                                <form enctype="multipart/form-data" action="uploader.php" method="POST">
                                <input name="uploadedfile" type="file" />
                                <input type="submit" value="Subir archivo" />
                                </form>
                            </td> 
                            <td class="center">
                                <a class="button block-shadow-info text-shadow alert" onclick="Desactivar(<?= $col->IdColores ?>)">Desactivar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <label id="col" style="display:none;"></label>
                            <td>
                                <b style="font-size: 1.3em" class="fg-darkEmerald"> Colores existentes:</b><br>
                                <div class="input-control select">   
                                    <select id="todo">                                    
                                        <option value="0">Selecciona el color</option>
                                            <?php foreach ($todos->result() as $todo): ?>
                                            <option value="<?= $todo->IdColores ?>"><?= $todo->Nombre ; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td class="center" rowspan="1" style="width: 30%">
                            </td> 
                            <td class="center">
                                <a class="button block-shadow-info text-shadow success" onclick="GuardarC()">Guardar Color</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="bordered">
                                <b style="font-size: 1.3em" class="fg-darkEmerald"> Nuevo color:</b><br>
                                <div class="input-control text" >
                                    <input type="text" id="nombreC" placeholder="Nombre color">
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
                                <a class="button block-shadow-info text-shadow success" onclick="GuardarNC()">Guardar Color</a>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</center>
<script>
    function Desactivar(color){
        
        var modelo =  $("#mod").text();
        $.post("DesactivarColor", {"color": color, "modelo":modelo}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El color se desactivo correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al desactivar el color");
            }
        });
    }
    
    function GuardarC(){
        var color = $("#todo").val();
        var modelo =  $("#mod").text();
        if(color ==0){
            Notificacion("Error", "Selecciona el color antes de continuar", "cancel", "alert");
            return(0);
        }
        //falta imagen
        $.post("SeleccionColor", {"color": color, "modelo":  modelo}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El color se guardó correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al guardar el color");
            }
        });
    }
    
    function GuardarNC(){
        var color = $("#nombreC").val();
        var modelo =  $("#mod").text();
        //falta imagen
        $.post("NuevoColor", {"color": color, "modelo": modelo}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("El color se guardó correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al guardar el color");
            }
        });
    }
</script>