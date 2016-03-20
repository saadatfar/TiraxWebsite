// ======================================================
// Themeone Slider
// ======================================================

jQuery(document).ready(function(){
	// Run themeone Slider
	TOMB_TOSlider.init();
});

var copyHelper;

var TOMB_TOSlider = {
	init: function() {
		jQuery('.themeone-slider-slides ul').sortable({
			cancel: '.to-slide-empty',
			helper: 'clone',
			forcePlaceholderSize: true,
			receive: function() {
				copyHelper = null;
			},
			update: function() {
				if (!jQuery(this).find('li').length) {
					jQuery('.themeone-slider-slides .to-slide-empty').fadeIn(300);
				} else {
					jQuery('.themeone-slider-slides .to-slide-empty').fadeOut(300);
				}
				var $this = jQuery('.themeone-slider-slides');
				var id = [];
				$this.find('li').each(function() {
					id.push(jQuery(this).attr('id'));
				});
				$this.closest('.tomb-field').find('input').val(id);
			},
		}).disableSelection();
		
		jQuery('.themeone-slides-drag ul').sortable({
			connectWith: '.themeone-slider-slides ul',
			forcePlaceholderSize: false,
			forceHelperSize: false,
			helper: function(e,li) {
				copyHelper = li.clone().insertAfter(li);
				return li.clone();
			},
			stop: function() {
				var func;
				func = copyHelper && copyHelper.remove();
			},
		});
		
		jQuery(document).on('click', '.to-slide-item-remove', function(){
			jQuery('.themeone-slider-slides ul').trigger('update');
			var $this = jQuery(this).closest('li');
			var $holder = jQuery(this).closest('.themeone-slider-slides');
			$this.closest('li').remove();
			var id = [];
			$holder.find('li').each(function() {
				id.push(jQuery(this).attr('id'));	
			});
			$holder.closest('.tomb-field').find('input').val(id);
	
			if ($holder.find('li').length < 1) {
				jQuery('.themeone-slider-slides .to-slide-empty').fadeIn(0);
				jQuery('.themeone-slider-slides .to-slide-empty').css('display', 'table-cell');
			} else {
				jQuery('.themeone-slider-slides .to-slide-empty').fadeOut(300);
			}
		});
	}
};