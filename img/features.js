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

function reply(parentId) {
	var kommDiv = document.getElementById('komm' + parentId);
    var answerArea = document.getElementById('answerArea');
    answerArea.style.display = 'inline';
    var hiddenParent = document.getElementById('hiddenParent');
    hiddenParent.value = parentId;
    var div = document.getElementById('parentComment');
    div.innerHTML = '<span style="color:red">You are replying to:<br></span>' + kommDiv.innerHTML;
}

function deleteKomm(kommId) {
    var hiddenKomm = document.getElementById('kommId');
    hiddenKomm.value = kommId;
    var formular = document.getElementById('kommDelete');
    formular.submit();
}

function displaythread(parentId) {
    var answerArea = document.getElementById('threadArea');
    answerArea.style.display = 'inline';
    var hiddenParent = document.getElementById('hiddenParent');
    hiddenParent.value = parentId;
    var div = document.getElementById('parentComment');
    div.innerHTML = '<span style="color:red">Your thread:<br></span>';  
}

function menuin(tdId) {
    var td = document.getElementById('menuTd' + tdId);
    td.style.backgroundColor = 'dimgrey';
}
function menuout(tdId) {
    var td = document.getElementById('menuTd' + tdId);
    td.style.backgroundColor = 'black';
}
