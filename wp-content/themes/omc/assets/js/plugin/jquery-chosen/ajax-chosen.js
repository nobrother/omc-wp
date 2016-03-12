// Generated by CoffeeScript 1.6.1

(function($) {
  var ajaxChosen, defaultOptions;
  defaultOptions = {
    minTermLength: 3,
    afterTypeDelay: 500,
    jsonTermKey: "term",
    keepTypingMsg: "Keep typing...",
    lookingForMsg: "Looking for"
  };
  return ajaxChosen = (function() {

    function ajaxChosen(element, settings, callback, chosenOptions) {
      var chosenXhr;
      this.element = element;
      chosenXhr = null;
      this.callback_function = callback;
      this.options = $.extend({}, defaultOptions, this.element.data(), settings);
      this.success = settings.success;
      this.element.chosen(chosenOptions ? chosenOptions : {});
      this.search_field = this.element.next('.chosen-container').find(".search-field > input, .chosen-search > input");
      this.register_observers();
    }

    ajaxChosen.prototype.register_observers = function() {
      var _this = this;
      this.search_field.keyup(function(evt) {
        _this.update_list(evt);
      });
      return this.search_field.focus(function(evt) {
        _this.search_field_focused(evt);
      });
    };

    ajaxChosen.prototype.search_field_focused = function(evt) {
      if (this.options.minTermLength === 0 && this.search_field.val().length === 0) {
        return this.update_list(evt);
      }
    };

    ajaxChosen.prototype.update_list = function(evt) {
      var msg, options, val, _this;
      this.untrimmed_val = this.search_field.val();
      val = $.trim(this.search_field.val());
      msg = val.length < this.options.minTermLength ? this.options.keepTypingMsg : this.options.lookingForMsg + (" '" + val + "'");
      this.element.next('.chosen-container').find('.no-results').text(msg);
      if (val === this.search_field.data('prevVal')) {
        return false;
      }
      this.search_field.data('prevVal', val);
      if (this.timer) {
        clearTimeout(this.timer);
      }
      if (val.length < this.options.minTermLength) {
        return false;
      }
      if (this.options.data == null) {
        this.options.data = {};
      }
      this.options.data[this.options.jsonTermKey] = val;
      if (this.options.dataCallback != null) {
        this.options.data = this.options.dataCallback(this.options.data);
      }
      _this = this;
      options = this.options;
      options.success = function(data) {
        return _this.show_results(data);
      };
      return this.timer = setTimeout(function() {
        if (_this.chosenXhr) {
          _this.chosenXhr.abort();
        }
        return _this.chosenXhr = $.ajax(options);
      }, options.afterTypeDelay);
    };

    ajaxChosen.prototype.show_results = function(data) {
      var items, nbItems, selected_values, _this;
      if (data == null) {
        return;
      }
      selected_values = [];
      this.element.find('option').each(function() {
        if (!$(this).is(":selected")) {
          return $(this).remove();
        } else {
          return selected_values.push($(this).val() + "-" + $(this).text());
        }
      });
      this.element.find('optgroup:empty').each(function() {
        return $(this).remove();
      });
      items = this.callback_function != null ? this.callback_function(data, this.search_field) : data;
      nbItems = 0;
      _this = this;
      $.each(items, function(i, element) {
        var group, text, value;
        nbItems++;
        if (element.group) {
          group = _this.element.find("optgroup[label='" + element.text + "']");
          if (!group.size()) {
            group = $("<optgroup />");
          }
          group.attr('label', element.text).appendTo(_this.element);
          return $.each(element.items, function(i, element) {
            var text, value;
            if (typeof element === "string") {
              value = i;
              text = element;
            } else {
              value = element.value;
              text = element.text;
            }
						
            if ($.inArray(value + "-" + text, selected_values) === -1) {
              return $("<option />").attr('value', value).html(text).appendTo(group);
            }
          });
        } else {
          if (typeof element === "string") {
            value = i;
            text = element;
          } else {
            value = element.value;
            text = element.text;
          }
					
          if ($.inArray(value + "-" + text, selected_values) === -1) {
            return $("<option />").attr('value', value).html(text).appendTo(_this.element);
          }
        }
      });
      if (nbItems) {
				var searchText = this.element.data().chosen.search_field.val();
        this.element.trigger("chosen:updated");
				this.element.data().chosen.search_field.val(searchText).trigger('keyup');
      } else {
        this.element.data().chosen.no_results_clear();
        this.element.data().chosen.no_results(this.search_field.val());
      }
      if (this.success != null) {
        this.success(data);
      }
      return this.search_field.val(this.untrimmed_val);
    };

    $.fn.ajaxChosen = function(options, callback, chosenOptions) {
      if (options == null) {
        options = {};
      }
      if (chosenOptions == null) {
        chosenOptions = {};
      }
      return this.each(function() {
				$(this).data('ajaxChosen', new ajaxChosen($(this), options, callback, chosenOptions));
        return $(this).data('ajaxChosen');
      });
    };

    return ajaxChosen;

  })();
})(jQuery);