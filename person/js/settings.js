(function ($) {

	FLBuilder.registerModuleHelper('person', {

		init: function () {
			var form = $('.fl-builder-settings'),
				buttonBgColor = form.find('input[name=btn_bg_color]'),
				photoCrop = form.find('select[name=photo_crop]');

			this._flipSettings();
			photoCrop.on('change', this._photoCropChanged);

			// Preview events.
			buttonBgColor.on('change', this._previewButtonBackground);
		},

		_flipSettings: function () {
			var form = $('.fl-builder-settings'),
				icon = form.find('input[name=icon]'),
				icon2 = form.find('input[name=btn_icon]');
			if (-1 !== icon.val().indexOf('fad fa')) {
				$('#fl-field-icon_duo_color1').show();
				$('#fl-field-icon_duo_color2').show();
				$('#fl-field-icon_color').hide();
				$('#fl-field-icon_hover_color').hide();
			} else {
				$('#fl-field-icon_duo_color1').hide();
				$('#fl-field-icon_duo_color2').hide();
				$('#fl-field-icon_color').show();
				$('#fl-field-icon_hover_color').show();
			}
			if (-1 !== icon2.val().indexOf('fad fa')) {
				$('#fl-field-btn_duo_color1').show();
				$('#fl-field-btn_duo_color2').show();
			} else {
				$('#fl-field-btn_duo_color1').hide();
				$('#fl-field-btn_duo_color2').hide();
			}
		},


		_previewButtonBackground: function (e) {
			var preview = FLBuilder.preview,
				selector = preview.classes.node + ' a.fl-button, ' + preview.classes.node + ' a.fl-button:visited',
				form = $('.fl-builder-settings:visible'),
				style = form.find('select[name=btn_style]').val(),
				bgColor = form.find('input[name=btn_bg_color]').val();

			if ('flat' === style) {
				if ('' !== bgColor && bgColor.indexOf('rgb') < 0) {
					bgColor = '#' + bgColor;
				}
				preview.updateCSSRule(selector, 'background-color', bgColor);
				preview.updateCSSRule(selector, 'border-color', bgColor);
			} else {
				preview.delayPreview(e);
			}
		},

		_photoCropChanged: function () {
			var form = $('.fl-builder-settings'),
				crop = form.find('select[name=photo_crop]'),
				radius = form.find('.fl-border-field-radius');

			if ('circle' === crop.val()) {
				radius.hide();
			} else {
				radius.show();
			}
		},
	});

})(jQuery);
