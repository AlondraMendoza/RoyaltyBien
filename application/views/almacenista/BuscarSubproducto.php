<script>


</script>

<?php foreach ($encontrados->result() as $encontrado): ?>
	<span class="mif-checkmark fg-green"></span> <a
		href="javascript: Seleccionar(<?= $id ?>,<?= $encontrado->IdCGriferia ?>,'<?= $encontrado->Clave . " | " . $encontrado->Descripcion ?>')"><?= $encontrado->Descripcion ?>
		| <?= $encontrado->Clave ?></a>

	<br>
<?php endforeach ?>
