<option value="0">Todos</option>
<?php foreach ($modelos->result() as $modelo): ?>
    <option value="<?= $modelo->IdModelos; ?>"><?= $modelo->Nombre; ?></option>
<?php endforeach; ?>