var reservitDomainName = "http://premium.logishotels.com"; // Votre nom de domaine sécurisé chez Interface Technologies https://secure.reservit.com
var reservitHotelId = "1366"; // Votre Hotelid chez Interface Technologies
var reservitCustdId = "233"; // Votre Custid chez Interface Technologies
var paramsWidget = {
	'clientid' : 'it-rest-public-c233-h1366', // ClientId, à récupérer auprès de votre chargé(e) clientèle
	'clientkey' : 'c85c177b-0186-4268-993c-d14d523c0492', // ClientKey, à récupérer auprès de votre chargé(e) clientèle
	'nbAdultMax' : 6, // Nombre maximum d'adultes selectionnable par l'utilisateur
	'nbChildMax' : 6, // Nombre maximum d'enfants selectionnable par l'utilisateur
	'bDisplayBestPrice' : false, // Determine l'affichage ou non du bloc présentant le meilleur tarif
	'langcode' : 'en', // Langue du widget
	'divContainerWidth' : '1000',  // Largeur (en px) du div contenant le widget, dans le cas d'une intégration en iframe (400px conseillé au minimum en largeur de l'iframe)
	'partidDistrib' : '', // Id du partenaire avec lequel comparer vos tarifs (partid), ce parametre est optionnel, vous pouvez donc ne pas le remplir
	'displayMode' : 'horizontal' // Affichage du Widget en mode horizontal ou vertical (valeurs à mettre : horizontal ou vertical)
};
function buildWidgetUrl(){
	var urlToCall = reservitDomainName+"/front"+reservitHotelId+"/front.do?m=widget&mode=init&custid="+reservitCustdId+"&hotelid="+reservitHotelId;

	for (key in paramsWidget) {
 		urlToCall += "&"+key+"="+paramsWidget[key];
	}

	// Si l'API Google Analytics est utilisée, faire suivre les paramètres pour l'iframe
	if (typeof _gaq != 'undefined' &&  typeof _gat != 'undefined' ) {
		var pageTracker = _gat._getTrackerByName();
		var linkerUrl = pageTracker._getLinkerUrl(urlToCall);
		urlToCall = linkerUrl;
	}

	return urlToCall;
}

// Intégration en iframe
function getWidgetInIframe(frameid){
	document.getElementById(frameid).src = buildWidgetUrl();
   
}
window.onload = function()
{
    getWidgetInIframe("myframe");
}

