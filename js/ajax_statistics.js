(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.ajax_statistics = {
    attach: function (context, settings) {
      $('body', context).once('ajax-statistics').each(function () {
      	var nid = 0;
      	if (drupalSettings.nid != null) {
      		nid = drupalSettings.nid;
      	}
	      $.post('/ajax_statistics_count/'+ nid, function(data, status){});
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
