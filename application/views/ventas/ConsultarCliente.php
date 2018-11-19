<ul class="simple-list align-left">
    <?php foreach ($clientes->result() as $r): ?>
        <li>
            <?= $r->Nombre ?>
            <i class="mif-plus fg-green" onclick="SeleccionarCliente(<?=$r->IdClientes ?>,'<?= $r->Nombre?>')"></i>
            <br>
        </li>
    <?php endforeach; ?>
</ul>
<script>
function SeleccionarCliente(id,nombre)
{
    $("#inputcliente").val(id);
    $("#textocliente").val(nombre);
    $("#divcliente").html("");
}
</script>