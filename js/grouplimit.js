document.getElementById('upltx').onkeyup = function() {
	limit = 1024;
	
	if (this.value.length > limit) {
		this.value = this.value.substr(0, limit);
	}
	document.getElementById('textlimit').innerHTML =
		`&nbsp;${this.value.length} / ${limit}<br>`;
	document.getElementById('textlimit').style.color =
		`rgb(0, ${ this.value.length / limit * -0 + 0 }, 0)`;
};

document.getElementById('upltx').onkeyup();