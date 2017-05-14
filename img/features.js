function klappen(nr) {
	var meindiv = document.getElementById('aufklappdiv' + nr);
	var bildchen = document.getElementById('bildchen' + nr);
	if (meindiv.style.display == 'none') {
		meindiv.style.display = 'inline';
		bildchen.src = 'img/MINUS.png';
	} else {
		meindiv.style.display = 'none';
		bildchen.src = 'img/PLUS.png';
	}
}
