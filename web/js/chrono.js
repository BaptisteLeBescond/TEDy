/***
 * Gestion du Timer de la page Planning En Cours
 ***/


// initialise les dixièmes
var dixieme = 0;
// initialise les secondes
var seconde = 0;
// initialise les minutes
var minute = 0;
// initialise les heures
var heure = 0;

var spanSeconde = document.getElementById('seconde');
var spanMinute = document.getElementById('minute');
var spanHeure = document.getElementById('heure');

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

	// si les minutes > 59, on les réinitialise à 0 et on incrémente les heures de 1
	if (minute > 59) {
		minute = 0;
		heure++;
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
	// on affiche les heures
	if (heure < 10)
		spanHeure.textContent = "0"+heure;
	else
		spanHeure.textContent = heure;
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
	heure = 0;
	spanSeconde.textContent = "0"+seconde;
	spanMinute.textContent = "0"+minute;
	spanHeure.textContent = "0"+heure;
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