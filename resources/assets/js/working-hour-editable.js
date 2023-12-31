/**
 Working hours editable input.
 Internally value stored as {start_time: "08:00", end_time: "17:00", info: "Be fast.."}

 @class workinghours
 @extends abstractinput
 @final
 @example
 <a href="#" id="workinghours" data-type="workinghours" data-pk="1">awesome</a>
 <script>
 $(function(){
    $('#address').editable({
        url: '/post',
        title: 'Enter opening, closing time and some info #',
        value: {
            start_time: "08:00",
            end_time: "17:00",
            info: "Something..."
        }
    });
});
 </script>
 **/
(function ($) {
    "use strict";

    var WorkingHours = function (options) {
        this.init('workinghours', options, WorkingHours.defaults);
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(WorkingHours, $.fn.editabletypes.abstractinput);

    $.extend(WorkingHours.prototype, {
        /**
         Renders input from tpl

         @method render()
         **/
        render: function() {
            this.$input = this.$tpl.find('input');
        },

        /**
         Default method to show value in element. Can be overwritten by display option.

         @method value2html(value, element)
         **/
        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return;
            }

            var html = '';

            if (value.start_time){
                html = $('<div>').text(value.start_time).html();
            }

            if(value.end_time){
                html += ' - '+ $('<div>').text(value.end_time).html();
            }
            if(value.info){
                if ($.trim(html)!=''){ html +=' ';}
                html += $('<div>').text(value.info).html()
            }

            $(element).html(html);
        },

        /**
         Gets value from element's html

         @method html2value(html)
         **/
        html2value: function(html) {
            /*
             you may write parsing method to get value by element's html
             e.g. "Moscow, st. Lenina, bld. 15" => {city: "Moscow", street: "Lenina", building: "15"}
             but for complex structures it's not recommended.
             Better set value directly via javascript, e.g.
             editable({
             value: {
             city: "Moscow",
             street: "Lenina",
             building: "15"
             }
             });
             */
            return null;
        },

        /**
         Converts value to string.
         It is used in internal comparing (not for sending to server).

         @method value2str(value)
         **/
        value2str: function(value) {
            var str = '';
            if(value) {
                for(var k in value) {
                    str = str + k + ':' + value[k] + ';';
                }
            }
            return str;
        },

        /*
         Converts string to value. Used for reading value from 'data-value' attribute.

         @method str2value(str)
         */
        str2value: function(str) {
            /*
             this is mainly for parsing value defined in data-value attribute.
             If you will always set value by javascript, no need to overwrite it
             */
            return str;
        },

        /**
         Sets value of input.

         @method value2input(value)
         @param {mixed} value
         **/
        value2input: function(value) {
            if(!value) {
                return;
            }
            this.$input.filter('[name="start_time"]').val(value.start_time);
            this.$input.filter('[name="end_time"]').val(value.end_time);
            this.$input.filter('[name="info"]').val(value.info);
        },

        /**
         Returns value of input.

         @method input2value()
         **/
        input2value: function() {
            return {
                start_time: this.$input.filter('[name="start_time"]').val(),
                end_time: this.$input.filter('[name="end_time"]').val(),
                info: this.$input.filter('[name="info"]').val()
            };
        },

        /**
         Activates input: sets focus on the first field.

         @method activate()
         **/
        activate: function() {
            this.$input.filter('[name="start_time"]').focus();
        },

        /**
         Attaches handler to submit form in case of 'showbuttons=false' mode

         @method autosubmit()
         **/
        autosubmit: function() {
            this.$input.keydown(function (e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });
        },
    });

    WorkingHours.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-working-hours"><label><span>Opening: </span><input type="text" name="start_time" class="input-small" placeholder="00:00"></label></div>'+
        '<div class="editable-working-hours"><label><span>Closing: </span><input type="text" name="end_time" class="input-small" placeholder="00:00"></label></div>'+
        '<div class="editable-working-hours"><label><span>Info: </span><input type="text" name="info" class="input"></label></div>',

        inputclass: ''
    });

    $.fn.editabletypes.workinghours = WorkingHours;

}(window.jQuery));
