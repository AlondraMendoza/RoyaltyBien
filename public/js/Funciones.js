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
	$(id).JsBarcode(codigo, {
		width: 2,
		height: 50,
		quite: 10,
		format: 'CODE128',
		displayValue: false,
		fontOptions: '',
		font: 'monospace',
		textAlign: 'center',
		margin: 3,
		fontSize: 15,
		backgroundColor: '',
		lineColor: '#000'
	});
	var canvas = document.getElementById('barcode');
	var img = canvas.toDataURL('image/png');
	$(imgid).html(
		'<center><img width="100px" src="' +
			img +
			'"> <br><span style="font-size:.3em">' +
			codigo +
			'<br>' +
			descripcion +
			'</span></center>'
	);
}
