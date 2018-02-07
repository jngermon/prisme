require('jquery-ui');

$.widget( "prisme.calculatorAge", {
    _create: function() {

        var mapping = this.element.data('mapping');

        if (!mapping.birthdate) {
            this._destroy();
            return ;
        }

        this.birthdate = $('[data-definition-name='+mapping.birthdate+']');

        if (!this.birthdate) {
            this._destroy();
            return ;
        }

        var id =this.birthdate.data('prefixe');
        this.nbDaysFromOriginInput = this.birthdate.find('#'+id+'_nbDaysFromOrigin');
        this.calendarInput = this.birthdate.find('#'+id+'_calendar');

        if (!this.nbDaysFromOriginInput || !this.calendarInput) {
            this._destroy();
            return ;
        }
    },
    _init: function() {
        this._on(this.nbDaysFromOriginInput, {
            'change': this.updateAge,
        });
        this._on(this.calendarInput, {
            'change': this.updateAge,
        });

        this.updateAge();
    },
    updateAge: function(event) {
        var data = {
            'nbDaysFromOrigin': this.nbDaysFromOriginInput.val(),
            'calendar': this.calendarInput.val()
        };

        var url = this.element.data('remote');

        if (this.jqXHR) {
            this.jqXHR.abort();
        }

        var that = this;

        this.jqXHR = $.ajax({
            url: url,
            data: data,
            dataType: 'json',
            success: function(data) {
                that.element.val(data.text);
            },
            complete: function(jqXHR, textStatus) {
                that.jqXHR = null;
            }
        });
    }
});