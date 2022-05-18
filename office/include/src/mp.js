function MaskedPassword(passfield, symbol, fullmask) {
	if (typeof document.getElementById == 'undefined'
		|| typeof document.styleSheets == 'undefined') { return false; }
	if (passfield == null) { return false; }
	this.symbol = symbol;
	this.isIE = typeof document.uniqueID != 'undefined';
	passfield.value = '';
	passfield.defaultValue = '';
	passfield._contextwrapper = this.createContextWrapper(passfield);
	this.fullmask = fullmask;
	var wrapper = passfield._contextwrapper;
	var hiddenfield = '<input type="hidden" id="' + passfield.id + '_real">';
	var textfield = this.convertPasswordFieldHTML(passfield);
	wrapper.innerHTML = hiddenfield + textfield;
	passfield = wrapper.lastChild;
	passfield.setAttribute('autocomplete', 'off');
	passfield._realfield = wrapper.firstChild;
	passfield._contextwrapper = wrapper;
	this.limitCaretPosition(passfield);
	var self = this;
	this.addListener(passfield, 'change', function (e) {
		self.fullmask = fullmask;
		self.doPasswordMasking(self.getTarget(e));
	});
	this.addListener(passfield, 'input', function (e) {
		self.fullmask = fullmask;
		self.doPasswordMasking(self.getTarget(e));
	});
	this.addListener(passfield, 'propertychange', function (e) {
		self.doPasswordMasking(self.getTarget(e));
	});
	this.addListener(passfield, 'keyup', function (e) {
		if (!/^(9|1[678]|224|3[789]|40)$/.test(e.keyCode.toString())) {
			self.fullmask = fullmask;
			self.doPasswordMasking(self.getTarget(e));
		}
	});
	this.addListener(passfield, 'blur', function (e) {
		if (fullmask == 1) {
			self.fullmask = 0;
		} else {
			self.fullmask = fullmask;
		}
		self.doPasswordMasking(self.getTarget(e));
	});
	this.addListener(passfield, 'focus', function (e) {
		if (fullmask == 1) {
			self.fullmask = 0;
		} else {
			self.fullmask = fullmask;
		}
		self.doPasswordMasking(self.getTarget(e));
	});
	return true;
}
MaskedPassword.prototype =
	{
		doPasswordMasking: function (textbox) {
			var plainpassword = '';
			if (textbox._realfield.value != '') {
				for (var i = 0; i < textbox.value.length; i++) {
					if (textbox.value.charAt(i) == this.symbol) {
						plainpassword += textbox._realfield.value.charAt(i);
					}
					else {
						plainpassword += textbox.value.charAt(i);
					}
				}
			}
			else {
				plainpassword = textbox.value;
			}
			var maskedstring = this.encodeMaskedPassword(plainpassword, this.fullmask, textbox);
			if (textbox._realfield.value != plainpassword || textbox.value != maskedstring) {
				textbox._realfield.value = plainpassword;
				textbox.value = maskedstring;
			}
		},
		encodeMaskedPassword: function (passwordstring, fullmask, textbox) {
			var characterlimit = fullmask;
			for (var maskedstring = '', i = 0; i < passwordstring.length; i++) {
				if (i < passwordstring.length - characterlimit) {
					if (passwordstring.charAt(i) === "-" && fullmask !== 0) {
						maskedstring += "-";
					} else {
						maskedstring += this.symbol;
					}
				}
				else {
					if (passwordstring.charAt(i) === "-" && fullmask !== 0) {
						maskedstring += "-";
					} else {
						maskedstring += passwordstring.charAt(i);
					}
				}
			}
			return maskedstring;
		},
		createContextWrapper: function (passfield) {
			var wrapper = document.createElement('span');
			wrapper.style.position = 'relative';
			passfield.parentNode.insertBefore(wrapper, passfield);
			wrapper.appendChild(passfield);
			return wrapper;
		},
		convertPasswordFieldHTML: function (passfield) {
			var textfield = '<input';
			for (var fieldattributes = passfield.attributes,
				j = 0; j < fieldattributes.length; j++) {
				textfield += ' ' + fieldattributes[j].name + '="' + fieldattributes[j].value + '"';
			}
			textfield += ' >';
			return textfield;
		},
		limitCaretPosition: function (textbox) {
			var timer = null, start = function () {
				if (timer == null) {
					if (this.isIE) {
						timer = window.setInterval(function () {
							var range = textbox.createTextRange(),
								valuelength = textbox.value.length,
								character = 'character';
							range.moveEnd(character, valuelength);
							range.moveStart(character, valuelength);
							range.select();
						}, 100);
					}
					else {
						timer = window.setInterval(function () {
							var valuelength = textbox.value.length;
							if (!(textbox.selectionEnd == valuelength && textbox.selectionStart <= valuelength)) {
								textbox.selectionStart = valuelength;
								textbox.selectionEnd = valuelength;
							}
						}, 100);
					}
				}
			},
				stop = function () {
					window.clearInterval(timer);
					timer = null;
				};
			this.addListener(textbox, 'focus', function () { start(); });
			this.addListener(textbox, 'blur', function () { stop(); });
		},
		addListener: function (eventnode, eventname, eventhandler) {
			if (typeof document.addEventListener != 'undefined') {
				return eventnode.addEventListener(eventname, eventhandler, false);
			}
			else if (typeof document.attachEvent != 'undefined') {
				return eventnode.attachEvent('on' + eventname, eventhandler);
			}
		},
		addSpecialLoadListener: function (eventhandler) {
			if (this.isIE) {
				return window.attachEvent('onload', eventhandler);
			}
			else {
				return document.addEventListener('DOMContentLoaded', eventhandler, false);
			}
		},
		getTarget: function (e) {
			if (!e) { return null; }
			return e.target ? e.target : e.srcElement;
		}
	}
