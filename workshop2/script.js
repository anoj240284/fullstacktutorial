
function myFunction(){
	var x = document.getElementByID("myTopnav");
	if (x.className === "topnav"){
		x.className += "responsive";
	} else {
		x.className = "topnav";
	}
}