<b>Defecto</b><br>
<div id="" class="input-control select full-size" style="height: 80px;">
    <select id="defecto<?= $ndef ?><?= $idprod ?>">
        <?php foreach ($defectos->result() as $defecto): ?>
            <option value="<?= $defecto->IdDefectos ?>"><?= $defecto->Nombre ?></option>
        <?php endforeach; ?>
    </select>
</div>