<script>
    function VerificarClave(){
        var cadena=$("#claveProd").val();
        if(cadena.length==21){
        var inicio = 13;
        var clave = cadena.substring(inicio);
        $("#des").html("Verificando clave");
        $.getJSON("VerificarClaveProd", {"clave": clave}, function (data) {
            if(data.id != null){
                $("#des").html("Producto encontrado");
                //metodo para Abrir tabla y agregar datos
                var input='<tr><td class="center">';
                input+='<b style="font-size: 1.3em" class="fg-darkEmerald">Descripción:</b><br>';
                input+=data.nombre;
                input+='</td>';
                input+='<td><label class="input-control checkbox">';
                input+='<input type="checkbox" name="IDS[]" value="'+data.id+'" checked>';
                input+='<span class="check"></span>';
                input+='</label></td></tr>';
                $("#tablaproductos").append(input);
                $("#claveProd").val("");
            }   
        });
        }
    }
    
    function Guardar(){
        $("input[name='IDS[]']:checked").each(function() {
            //alert($(this).val());
            $.post("GuardarAlmacen", {"id": $(this).val()}, function (data) {
            if (data == "bien")
            {
                $.Notify({
                caption: 'Correcto',
                content: 'Los productos se guardarón correctamente',
                type: 'success'
                });
            } else
            {
                $.Notify({
                caption: 'Error',
                content: 'Ocurrió un error al guardar los productos',
                type: 'alert'
                });
            }
        });
        });
        $("#tablaproductos").empty();
    }
    
    function Cancelar(){
        location.reload(true);
    }
</script>
<h1><b> ENTRADA DE PRODUCTOS</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Ingresar Código de Barras</span>
        </div>
        <div class="content" id="Inicio">
            <table class="table">
                <tr>
                    <td class="center">
                        <b style="font-size: 1.3em" class="fg-darkEmerald"> Código de Barras:</b><br>
                        <div class="input-control text full-size" style="height:80px;font-size: x-large">
                            <input type="text" id="claveProd" onkeyup="VerificarClave()">
                        </div>
                         <br><label><span id="des"></span></label>
                    </td> 
                </tr>
                    <br><br>
                </table>
            </div>
        </div>
    <div id="ResultadosAlmacen">
        <center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Detalle de Productos</span>
        </div>
        <div class="content" id="Resultados">
        <table class="table" id="tablaproductos">
        <tr> 
        </tr>
        </table>
        <table>
        <tr>
            <td class="center" id="Botones"><br>
                <div class="input-control text big-input medium-size">
                <button class="button success" onclick="Guardar()">Guardar</button></div>
                <div class="input-control text big-input medium-size">
                <button class="button danger" onclick="Cancelar()">Cancelar</button></div>
            </td>
        </tr>
        </table>
        </div>
    </div>
</center>
    </div>
</center><br><br><br>


