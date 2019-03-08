function MsjError(texto) {
	$.Notify({
		caption: 'Error',
		content: texto,
		icon: "<span class='mif-cancel'></span>",
		type: 'alert'
	});
}
function MsjCorrecto(texto) {
	$.Notify({
		caption: 'Correcto',
		content: texto,
		icon: "<span class='mif-checkmark'></span>",
		type: 'success'
	});
}
function CodigoBarras(id, codigo, imgid, descripcion) {
	/*Parámetro id no se debe enviar con el # sólo el id*/
	$('#' + id).JsBarcode(codigo, {
		width: 1,
		height: 10,
		quite: 10,
		format: 'CODE128',
		displayValue: false,
		fontOptions: '',
		font: 'monospace',
		textAlign: 'center',
		margin: 0,
		fontSize: 15,
		backgroundColor: '',
		lineColor: '#000'
	});
	var canvas = document.getElementById(id);
	
	var img = canvas.toDataURL('image/png');
	$(imgid).html(
		'<center><img width="100%" src="' +
			img +
			'"> <br><span style="font-size:.5em">' +
			codigo +
			'<br>' +
			descripcion +
			'</span></center>'
	);
}
