/* http://keith-wood.name/countdown.html
 * Spanish initialisation for the jQuery countdown extension
 * Written by Sergio Carracedo Martinez webmaster@neodisenoweb.com (2008) */
(function($) {
	$.countdown.regional['es'] = {
	//	labels: ['A&ntilde;os', 'Meses', 'Semanas', 'D&iacute;a(s)', 'Hora(s)', 'Minutos', 'Segundos'],
	//	labels1: ['A&ntilde;os', 'Meses', 'Semanas', 'D&iacute;a(s)', 'Hora(s)', 'Minutos', 'Segundos'],
		labels: ['A', 'M', 'S', 'd', 'h', 'm', 's'],
		compactLabels: ['a', 'm', 's', 'g'],
		timeSeparator: ':', isRTL: false};
	$.countdown.setDefaults($.countdown.regional['es']);
})(jQuery);
