<ul style="margin-left:10px" class="simple-list align-left" >
    <?php foreach ($clientes->result() as $r): ?>
        <li onclick="SeleccionarCliente(<?=$r->IdClientes ?>,'<?= $r->Nombre?>')" style="cursor:pointer">
            <?= $r->Nombre ?> 
            <i class="mif-checkmark fg-green" onclick="SeleccionarCliente(<?=$r->IdClientes ?>,'<?= $r->Nombre?>')"></i>
            <br>
        </li>
    <?php endforeach; ?>
</ul>
<script>
function SeleccionarCliente(id,nombre)
{
    $("#cliente").val(id);
    $("#textocliente").val(nombre);
    $("#divcliente").html("");
}
</script>