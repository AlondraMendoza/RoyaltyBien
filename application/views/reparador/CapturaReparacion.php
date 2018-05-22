<h1><b>REPARACIÓN</b></h1><br>
<center>
    <div class="panel warning" data-role="panel">
        <div class="heading">
            <span class="icon mif-stack fg-white bg-darkOrange"></span>
            <span class="title">Información Básica de reparación</span>
        </div>
        <div class="content">
            <table class="table">
                <tr>
                    <td class="center">
                        <b>Diagnóstico:</b><br><br>
                        <textarea id="diagnostico" data-role="textarea" data-auto-size="true" data-max-height="200"></textarea>
                    </td> 
                    <td class="center">
                        <b>Solución:</b><br><br>
                        <textarea id="solucion" data-role="textarea" data-auto-size="true" data-max-height="200"></textarea>
                        </td>
                    <td class="center"><br><br>
                        <a class="button block-shadow-info text-shadow success" onclick="Guardar(<?=$ProductoId ?>)" >Guardar</a>
                        <a class="button block-shadow-info text-shadow alert" onclick="Cancelar()">Cancelar</a>
                    </td>
                </tr>
                <br><br>
            </table>
        </div>
    </div>
</center>
<script>
    function Guardar(producto){
        var diagnostico =  $("#diagnostico").val();
        var solucion =  $("#solucion").val();
        $.post("GuardarReparacion", {"producto": producto, "diagnostico":diagnostico, "solucion":solucion}, function (data) {
            if ($.trim(data) === "correcto")
            {
                MsjCorrecto("Los datos se guardarón correctamente");
                location.reload();
            } else
            {
                MsjError("Ocurrió un error al guardar los datos");
            }
        });
    }
</script>