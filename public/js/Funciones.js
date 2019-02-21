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
		width: 2,
		height: 20,
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
		'<center><img style="" width="400px" id="imagenconvertida" height="100px" src="' +
			img +
			'"> <br><span style="font-size:.5em;" id="textoetiqueta" >' +
			codigo +
			'<br>' +
			descripcion +
			'</span></center>'
	);
	//document.getElementById('imagenconvertida').style.transform = 'rotate(' + 90 + 'deg)';
	$(imgid).rotate(90);
	
}
