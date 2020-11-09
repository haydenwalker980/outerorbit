document.getElementById('upltx2').onkeyup = function() {
	upltx2 = 1000;
	
	if (this.value.length > upltx2) {
		this.value = this.value.substr(0, upltx2);
	}
	document.getElementById('textlimit').innerHTML =
		`&nbsp;${this.value.length} / ${upltx2}<br>`;
	document.getElementById('textlimit').style.color =
		`rgb(0, ${ this.value.length / upltx2 * -0 + 0 }, 0)`;
};

document.getElementById('upltx2').onkeyup(); 