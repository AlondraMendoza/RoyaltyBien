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
		width: 4,
		height: 20,
		quite: 10,
		format: 'CODE128',
		displayValue: false,
		fontOptions: '',
		font: 'monospace',
		textAlign: 'center',
		marginTop: 0, // Default
		marginBottom: 0, // Default
		marginLeft: 0, // Default
		marginRight: 0, // Default
		fontSize: 1,
		backgroundColor: '',
		lineColor: '#000'
	});
	var canvas = document.getElementById(id);

	var img = canvas.toDataURL('image/png');
	$(imgid).css('margin-top', '0px');
	$(imgid).html(
		'<center style="border:red solid 1px"><img style="width:50%;height:20px" src="' +
			img +
			'"> <br><span style="font-size:.5em">' +
			codigo +
			'<br>' +
			descripcion +
			'</span></center>'
	);
}
