function toast(msg) {
    var x = document.getElementById("toast");
	x.className = "show";
	if (msg === 'notfound')
		msg = "Data is not found";
	x.innerHTML = msg;
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}