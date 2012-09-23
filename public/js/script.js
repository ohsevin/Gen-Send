/* Author:

*/
$(document).ready(function() {
	if($('.col1').height() > $('.col2').height())
	{
		$('.col2').height($('.col1').height());
	}
	else
	{
		$('.col1').height($('.col2').height());
	}
});




