/*
* CakePHP jQuery Validation Plugin
* Copyright (c) 2009 Matt Curry
* www.PseudoCoder.com
* http://github.com/mcurry/cakephp_plugin_validation
* http://sandbox2.pseudocoder.com/demo/validation
*
* @author      mattc <matt@pseudocoder.com>
* @license     MIT
*
*/

(function($) {
	var options = null;
	var errors = [];

	$.fn.validate = function(rules, opts) {
		console.debug(rules);
		options = $.extend({}, $.fn.validate.defaults, opts);

		$.each(opts.watch, function(fieldId) {
			$("#" + opts.watch[fieldId]).change(function() {
				$.fn.validate.ajaxField($(this));
			});
		});

		$.each(rules, function(field) {
			var input = $("#" + field);
			var validationRules = this;
			input.blur(function() {
				$(this)
				.removeClass("form-error")
				.parent()
				.removeClass("error")
				.children("div.error-message").remove().end()
				.children(".checkmark").remove();
				$.fn.validate.validateField(field,validationRules);
				if (input.parent('div').hasClass('error')) {
					input.keyup(function(event) {
						$(this)
						.removeClass("form-error")
						.parent()
						.removeClass("error")
						.children("div.error-message").remove().end()
						.children(".checkmark").remove();
						$.fn.validate.validateField(field, validationRules);
					});
				}
			});
		});

		return this.each(function() {
			$this = $(this);
			$this.submit(function() {
				$.fn.validate.beforeFilter();
				errors = [];
				$.each(rules, function(field) {
					result = $.fn.validate.validateField(field,this);
					if (typeof result != "undefined") {
						return result;
					}
				});

				$.fn.validate.afterFilter(errors);

				if(errors.length > 0) {
					return false;
				}

				return true;
			});
		});
	};

	$.fn.validate.validateField = function (field,validationRules) {
		var val = $("#" + field).val();
		var fieldName = $('#' + field).attr('name');
		if(typeof val == "string") {
			val = $.trim(val);
		}
		$.each(validationRules, function() {
			if ($("#" + field).attr("id") == undefined) {
				return true;
			}

			if (this['allowEmpty'] && typeof val == "string" && val == '') {
				return true;
			}

			if (this['allowEmpty'] && typeof val == "object" && val == null) {
				return true;
			}

			if (!$.fn.validate.validateRule(val, this['rule'], this['negate'], fieldName, field)) {

				errors.push(this['message']);
				$.fn.validate.setError(field, this['message']);

				if (this['last'] === true) {
					return false;
				}
			}
		});
		if ($('#' + field).siblings('div.error-message').length == 0 && $('#' + field).siblings('.checkmark').length == 0) {
			$('#' + field).after('<div style="display:inline;" class="checkmark"></div>');
		}

	}

	$.fn.validate.validateRule = function(val, rule, negate, fieldName, field) {
		if(negate == undefined) {
			negate = false;
		}
		//handle custom functions
		if(typeof rule == 'object') {

			if($.fn.validate[rule.rule] != undefined) {
				return $.fn.validate[rule.rule](val, rule.params, fieldName, field);
			} else {
				return true;
			}
		}

		//handle regex rules
		if (rule.charAt(0) == "/" && (rule.substr(-1)== "/" || rule.substr(-2,1)== "/")) {
			if (negate && val.match(eval(rule))) {
				return false;
			} else if (!negate && !val.match(eval(rule))) {
				return false;
			}
		}
		return true;
	};

	$.fn.validate.checkbox = function(val, params, fieldName, field) {
		return $('#' + field).prop('checked');
	};

	$.fn.validate.confirmPassword = function(val, params, fieldName, field) {
		$checkAgainst = $('#' + field.replace(params[0],params[1]));
		return $checkAgainst.val() == $('#' + field).val();
	}

	$.fn.validate.boolean = function(val) {
		return $.fn.validate.inList(val, [0, 1, '0', '1', true, false]);
	};

	$.fn.validate.comparison = function(val, params) {
		if(val == "") {
			return false;
		}

		val = Number(val);
		if(val == "NaN") {
			return false;
		}

		if(eval(val + params[0] + params[1])) {
			return true;
		}

		return false;
	};

	$.fn.validate.inList = function(val, params) {
		if(params != null) {
			if($.inArray(val, params) == -1) {
				return false;
			}
		}

		return true;
	};

	$.fn.validate.range = function(val, params) {
		if (val < parseInt(params[0])) {
			return false;
		}
		if (val > parseInt(params[1])) {
			return false;
		}

		return true;
	};

	$.fn.validate.multiple = function(val, params) {
		if(typeof val != "object" || val == null) {
			return false;
		}

		if(params.min != null && val.length < params.min) {
			return false;
		}
		if(params.max != null && val.length > params.max) {
			return false;
		}

		if(params["in"] != null) {
			for(i = 0; i < params["in"].length; i ++) {
				if($.inArray(params["in"][i], val) == -1) {
					return false;
				}
			}
		}

		return true;
	};

	$.fn.validate.ajaxField = function($field) {
		$.fn.validate.clearError($field);
		$.fn.validate.ajaxBeforeFilter($field);

		var data = new Object;
		data[$field.attr("name")] = $field.val();
		$.post(options.root + "js_validate/field/" + $field.attr("id"), data,
		function(validates) {
			$.fn.validate.ajaxAfterFilter($("#" + validates.field));
			if(!validates.result) {
				$.fn.validate.setError(validates.field, validates.message);
			}
		},
		"json");
	}

	$.fn.validate.ajaxBeforeFilter = function($field) {
		$field.after("<img class=\"ajax-loader\" src=\"" + options.root + "js_validate/img/ajax-loader.gif\">");
	}

	$.fn.validate.ajaxAfterFilter = function($field) {
		$field.siblings(".ajax-loader").remove();
	}

	$.fn.validate.clearError = function($field) {
		if(typeof $field == "string") {
			$field = $("#" + field);
		}

		$field.removeClass("form-error")
		.parents("div:first").removeClass("error")
		.children(".error-message").remove();
	}

	$.fn.validate.setError = function(field, message) {
		$("#" + field).addClass("form-error")
		.parents("div:first").addClass("error")
		.append('<div class="error-message">'  + message +  '</div>');
	};

	$.fn.validate.noError = function($field) {
		if ($field.siblings('div.checkmark').length == 0 && $field.siblings('div.error-message').length == 0)
			$field.after('<div class="checkmark" style="display:inline;"><img src="/phimarket/phimarket/img/checkmark.png" /></div>');;
	};

	$.fn.validate.beforeFilter = function() {
		if(options.messageId != null) {
			$("#" + options.messageId).html("")
			.slideDown();
		}

		$(".error-message").remove();
		$("input").removeClass("form-error");
		$("div").removeClass("error")
	};

	$.fn.validate.afterFilter = function(errors) {
		if(options.messageId != null) {
			$("#" + options.messageId).html(errors.join("<br />"))
			.slideDown();
		}
	};
})(jQuery);