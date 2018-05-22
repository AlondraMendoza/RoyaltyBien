<h1><b> EXPEDIENTE PRODUCTO</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica</span>
        </div>
        <div class="content">
            <table class="table">
                <?php if($producto==null){ ?>
                <?= "El producto no esta clasificado para reparar"?>
                <?php } else{ ?>
                <tr>
                    <td class="center" rowspan="2" style="width: 30%">
                        <img src="<?= base_url() ?>public/imagenes/<?= $producto->foto; ?>" height="190px;" width="190px;" title="<?= $producto->NombreProducto; ?>">        
                        <br><br>
                        <img src="barcodevista?text=<?= $codigo ?>"><br>
                        <?= $codigo; ?>
                    </td> 
                </tr>
                <tr>
                    <td>
                        <b>Producto:</b><br><br><?= $producto->NombreProducto; ?>
                        <hr>
                        <b>Modelo:</b><br><br><?= $producto->Modelo; ?>
                        <hr>
                        <b>Color:</b><br><br><?= $producto->Color; ?>
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
            <span class="title">Estados actuales del producto</span>
        </div>
        <div class="content" id="">
            <table class="table">
                <tr>
                    <td class="center">
                        <b>Defectos</b>
                        <br><br>
                        <?php
                        if ($defectos != null) {
                            foreach ($defectos->result() as $defecto):
                                ?>
                                <?= $defecto->Nombre ?><br>
                                <?php
                            endforeach;
                        } else {
                            echo "Sin defectos Registrados";
                        }
                        ?>
                    </td>
                    <td class="center">
                        <b>Diagnóstico :</b><br><br>
                        <div class="input-control select" >   
                            <select id="diagnostico" onchange="Seleccion()">                                    
                                <option value="0">Selecciona el diagnóstico</option>
                                    <?php foreach ($diagnostico->result() as $d): ?>
                                        <option value="<?= $d->IdCReparaciones ?>"><?= $d->Nombre . ""; ?></option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                    </td>
                    <td class="center">
                        <b>Solución :</b><br><br>
                        <div class="input-control select" >   
                            <select id="solucion" onchange="SeleccionS()">                                    
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </td>
                    <td class="center">
                        <b>Acciones</b>
                        <br><br>
                        <a class="button block-shadow-info text-shadow primary" onclick="Guardar(<?= $producto->IdProductos ?>)">Reparar</a>
                        <a class="button block-shadow-info text-shadow alert" onclick="Cancelar()">Cancelar</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    
    </center><br><br><br>

<script>
    
    function Seleccion()
    {
        $("#tddiagnostico").html($("#diagnostico option:selected").text());
    }
    
    function SeleccionS()
    {
        $("#tdsolucion").html($("#solucion option:selected").text());
    }
    
    function Guardar(producto){
        var diagnostico =  $("#diagnostico").val();
        var solucion =  $("#solucion").val();
        $.post("GuardarReparacion", {"producto": producto, "diagnostico":diagnostico, "solucion":solucion}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("Los datos se guardarón correctamente");
            } else
            {
                MsjError("Ocurrió un error al guardar los datos");
            }
        });
    }
</script>

