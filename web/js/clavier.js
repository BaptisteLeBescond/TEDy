/***
 * Gestion du clavier numérique de la Page Contrat
 */


var imageGrid = document.getElementById('grid');

$(imageGrid).click(function() {
	/**** Faire apparaître le clavier au clic sur le grid *****/
});



var clavier1 = document.getElementById('1');
var clavier2 = document.getElementById('2');
var clavier3 = document.getElementById('3');
var clavier4 = document.getElementById('4');
var clavier5 = document.getElementById('5');
var clavier6 = document.getElementById('6');
var clavier7 = document.getElementById('7');
var clavier8 = document.getElementById('8');
var clavier9 = document.getElementById('9');

var inputClavier = document.getElementById('inputClavier');

$(clavier1).click(function() {
	$(inputClavier).val($(inputClavier).val()+"1");
});
$(clavier2).click(function() {
	$(inputClavier).val($(inputClavier).val()+"2");
});
$(clavier3).click(function() {
	$(inputClavier).val($(inputClavier).val()+"3");
});
$(clavier4).click(function() {
	$(inputClavier).val($(inputClavier).val()+"4");
});
$(clavier5).click(function() {
	$(inputClavier).val($(inputClavier).val()+"5");
});
$(clavier6).click(function() {
	$(inputClavier).val($(inputClavier).val()+"6");
});
$(clavier7).click(function() {
	$(inputClavier).val($(inputClavier).val()+"7");
});
$(clavier8).click(function() {
	$(inputClavier).val($(inputClavier).val()+"8");
});
$(clavier9).click(function() {
	$(inputClavier).val($(inputClavier).val()+"9");
});



var boutonClavier = document.getElementById('boutonClavier');

$(boutonClavier).click(function() {
	console.log($(inputClavier).val());
	$(inputClavier).val('');
	/***** Faire disparaître le clavier *****/
	/***** Faire apparaître un jeton *****/
})