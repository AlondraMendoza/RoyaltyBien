
<script>

    var Producto = "";
    var Modelo = "";
    var Color = "";
    var Clasi = 0;
    

    function AbrirModelos(id)
    {
       // $("#tdproducto").html($("#producto" + id).text());
        Producto = id;
        $("#MostrarModelos").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando modelos');
        $("#MostrarModelos").load("ObtenerModelos", {"id": id, "withouttem": 1});
    }

    function AbrirColores(id)
    {
        //$("#tdmodelo").html($("#modelo" + id).text());
        Modelo = id;
        $("#MostrarColores").html('<span style="font-size:5em" class="mif-spinner5 mif-ani-spin"></span> <br>Cargando colores');
        $("#MostrarColores").load("ObtenerColores", {"id": id, "withouttem": 1});
    }

    function VerOtros(id)
    {
        //$("#tdcolor").html($("#color" + id).text());
        Color = id;
        $("#DivOtros").fadeIn();
    }
    
    function Seleccion()
    {
        Clasi =$("#clas option:selected").val();
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

    function Siguiente()
    {
        
        var prod = Producto;
        var mod = Modelo;
        var col = Color;
        var piezas = $("#piezas").val();
        var clasi = Clasi;
        if(piezas == ""){
            Notificacion("Error", "Captura el número de piezas antes de continuar", "cancel", "alert");
            return(0);
        }
        if(clasi == 0){
            Notificacion("Error", "Elige la clasificación", "cancel", "alert");
            return(0);
        }
        for (var i = 1; i <= piezas; i++) {
            var tr= "<tr><td id='tdNo'>"+ i +
            "<td id='tdproducto'>"+$("#producto" + prod).text()+"</td>"+
            "<td id='tdmodelo'>"+$("#modelo" + mod).text()+"</td>"+      
            "<td id='tdcolor'>"+$("#color" + col).text()+"</td>"+
            "<td id='tdclasificacion'>"+$("#clas option:selected").text()+"</td>"+
            "<td id='tdAccion"+i+"'><div class='input-control text big-input medium-size' id='boton' >\n\
            <button class='button success' onclick='GuardarDatos("+i+")'>Imprimir</button></div></td></tr>";
           $("#ListaTabla").append(tr);
        }       
    }
    
    function GuardarDatos(i){
        var prod = Producto;
        var mod = Modelo;
        var col = Color;
        var clasi = Clasi;
        
        $.getJSON("Guardado", {"prod": prod, "mod": mod, "col": col, "clasi": clasi},function(data){
             if (data.codigo != null)
            {
                $("#tdAccion" + i).html('<span class="mif-checkmark fg-green"></span> Listo');
                Notificacion("Correcto", "Guardado correctamente", "check", "success");
                CodigoBarras("barcode", data.codigo, "#imgbarcode", data.descripcion);
                $("#imgbarcode").printArea();
            } else
            {
                Notificacion("Error", "Ocurrió un error al guardar", "cancel", "alert");
            }
        });
    }
    
    function Cancelar(){
    location.reload();
       
    }
    
</script>
<h1><b> CAPTURA DE PRODUCTOS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar datos</span>
        </div>
        <div class="content" id="Inicio">
            <div id="MostrarProd">
                <b style="font-size: 1.3em" class="fg-darkEmerald">Selecciona el Producto:</b>
                <div class="grid" >
                    <div data-role="group" data-group-type="one-state">   
                        <?php if ($productos->num_rows() == 0) { ?>
                            <label>No hay Productos</label>
             
           <?php } else { ?>
                            <?php foreach ($productos->result() as $prod): ?>
                                <?php
                                $ci = &get_instance();
                                $ci->load->model("modelocedis");
                                //$npen = $ci->modelocapturista->ListarModelos($prod->IdCProductos);
                                ?>
                                <button id="producto<?= $prod->IdCProductos ?>" class="button" style='width: 210px; height: 210px;' onclick="AbrirModelos(<?= $prod->IdCProductos ?>)">
                                    <input  type="image" src="<?php echo base_url() ?>public/imagenes/<?= $prod->Imagen ?>" height="190px;" width="190px;" title="<?= $prod->Nombre ?>"/><b>
                                <?= $prod->Nombre ?></b></button>
                            <?php endforeach; ?>
<?php } ?>
                    </div>
                </div>
            </div>
            <hr style="background-color: gray;height: 1px;">
            <div id="MostrarModelos"></div>
            <hr style="background-color: gray;height: 1px;">
            <div id="MostrarColores"></div>
            <hr style="background-color: gray;height: 1px;">
            <div id="DivOtros" style="display: none">
                <table class="table">
                    <tr>
                        <td style="width: 32%" class="center">
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Cantidad de piezas:</b><br> 
                            <div class="input-control text full-size" style="height:80px;font-size: x-large">
                                <input type="text" id="piezas" placeholder="Teclea la cantidad">
                            </div>
                        </td>
                        <td style="width: 32%" class="center">
                            <b style="font-size: 1.3em" class="fg-darkEmerald">Clasificación:</b><br> 
                            <select class="select full-size" id="clas" onchange="Seleccion()">
                                <option value="0">Selecciona la clasificación</option>
                                <?php foreach ($clasificacion->result() as $c): ?>
                                    <option value=<?= $c->IdClasificaciones ?>><?= $c->Letra ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td><br><br></td></tr>
                    <tr>
                        <td></td>
                        <td class="center" id="Botones"><br>
                            <div class="input-control text big-input medium-size">
                                <button class="button success" onclick="Siguiente()">Siguiente</button></div>
                            <div class="input-control text big-input medium-size">
                                <button class="button danger" onclick="Cancelar()">Cancelar</button></div>
                        </td>
                    </tr>
                    <br><br>
                </table>
            </div>
        </div>
    </div><br><br>
    <div class="panel primary" data-role="panel" id="" style="z-index: 1">
        <div class="heading" style="position:relative;z-index: 1">
            <span class="icon mif-stack fg-white bg-darkBlue"></span>
            <span class="title">Productos</span>
        </div>
        <div class="content" id="">
            <div id="Resultados">
                <table class="table" id="ListaTabla" style="text-align:center;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Producto:</th>
                            <th>Modelo:</th>
                            <th>Color:</th>
                            <th>Clasificación:</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="trprevia">
                            <td id="tdNo"></td>
                            <td id="tdproducto"></td>
                            <td id="tdmodelo"></td>
                            <td id="tdcolor"></td>
                            <td id="tdclasificacion"></td>
                            <td id="tdAccion"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
        </div>
    </div>
    <br>
    <br>
</center><br><br><br>
<div style="display: none">
     <div id="areaimprimir"></div>
</div>
<div style="display:none">
        <div id="imgbarcode"></div>
        <canvas id="barcode"></canvas>
    </div>


