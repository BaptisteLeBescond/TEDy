/***
 * Gestion du Timer de la page Planning En Cours
 ***/


// initialise les dixièmes
var dixieme = 0;
// initialise les secondes
var seconde = 0;
// initialise les minutes
var minute = 0;

var spanSeconde = document.getElementById('seconde');
var spanMinute = document.getElementById('minute');

function play() {
	// incrémentation des dixièmes de 1
	dixieme++;

	// si les dixièmes > 9, on les réinitialise à 0 et on incrémente les secondes de 1
	if (dixieme > 9) {
		dixieme = 0;
		seconde++;
	} 

	// si les secondes > 59, on les réinitialise à 0 et on incrémente les minutes de 1
	if (seconde > 59) {
		seconde = 0;
		minute++;
	} 

	// on affiche les secondes
	if (seconde < 10)
		spanSeconde.textContent = "0"+seconde;
	else
		spanSeconde.textContent = seconde;
	// on affiche les minutes
	if (minute < 10)
		spanMinute.textContent = "0"+minute;
	else
		spanMinute.textContent = minute;
	// la fonction est relancée toutes les 10° de secondes
	compte = setTimeout('play()', 100);
}

// fonction qui remet les compteurs à 0
function stop() { 
	//arrête la fonction play()
	clearTimeout(compte) ;
	dixieme = 0;
	seconde = 0;
	minute = 0;
	spanSeconde.textContent = seconde;
	spanMinute.textContent = minute;
}



$('#play').click(function() {
	$('#play').hide();
	$('#pause').show();
	play();
});

$('#pause').click(function() {
	$('#pause').hide();
	$('#play').show();
	clearTimeout(compte);
});

$('#stop').click(function() {
	stop();
	$('#pause').hide();
	$('#play').show();
});