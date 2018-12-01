<script>
    function Notificacion(titulo, texto, icono, color)
    {
        $.Notify({
            caption: titulo,
            content: texto,
            icon: "<span class = 'mif-" + icono + "'></span>",
            type: color
        });
    }

    function CargaInfo(id)
    {
        if (!$("#" + id).hasClass("cargado")) {
            $("#" + id).html("Consultando información");
            $("#" + id).load("CargaInfoModeloCX", {"modelo_id": id});
            $("#" + id).addClass("cargado");
        }

    }
</script>
<h1 class="light text-shadow">INVENTARIO CEDIS REDUCIENDO PEDIDOS LIBERADOS</h1><br>

<div class="tabcontrol" data-role="tabcontrol" data-save-state="true" id='tabs'>
    <ul class="tabs">

        <?php
        $cont = 1;
        foreach ($modelos->result() as $modelo):
            $activo = "";
            if ($cont == 1) {
                $activo = "active";
            }
            ?>
            <li class="<?= $activo ?>"><a href="#<?= $modelo->IdModelos ?>" onclick="CargaInfo(<?= $modelo->IdModelos ?>)"><?= $modelo->Nombre ?></a></li>
            <?php
            $cont++;
        endforeach;
        ?>
    </ul>
    <div class="frames">
        <?php foreach ($modelos->result() as $modelo): ?>
            <div class="frame" id="<?= $modelo->IdModelos ?>">

            </div>
        <?php endforeach; ?>
    </div>
</div>