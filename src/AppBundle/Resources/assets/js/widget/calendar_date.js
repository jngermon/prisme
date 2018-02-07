require('jquery-ui');

$.widget( "prisme.calendarDate", {
    _create: function() {
        var id =this.element.data('prefixe');

        this.nbDaysFromOriginInput = this.element.find('#'+id+'_nbDaysFromOrigin');
        this.calendarInput = this.element.find('#'+id+'_calendar');

        this.yearInput = this.element.find('#'+id+'_year');
        this.monthInput = this.element.find('#'+id+'_month');
        this.dayInput = this.element.find('#'+id+'_day');

        this.input = this.element.find('#'+id);
        this.text = this.element.find('#'+id+'_text');

        this.monthOptions = [];
        var that = this;
        this.monthInput.find('option').each(function(idx, item) {
            var $item = $(item);
            that.monthOptions[idx] = {
                value: $item.val(),
                text: $item.html(),
                nbDays: $item.data('nb-days'),
                number: $item.data('month-number'),
                relatedCalendar: $item.data('related-calendar')
            };
        });
    },
    _init: function() {
        this._on(this.calendarInput, {
            'change': this.updateCalendar,
        });
        this._on(this.yearInput, {
            'change': this.updateDate,
        });
        this._on(this.monthInput, {
            'change': this.updateDate,
        });
        this._on(this.dayInput, {
            'change': this.updateDate,
        });

        this.updateCalendar();
    },
    updateCalendar: function(event) {
        this.yearInput.prop('disabled', true);
        this.monthInput.prop('disabled', true);
        this.dayInput.prop('disabled', true);

        var calendarId = this.calendarInput.val();

        // Use only month related to current calendar
        this.monthInput.find('option').remove();
        this.monthInput.append(this._getMonthForCalendar(calendarId));

        var data = {
            'nbDaysFromOrigin': this.nbDaysFromOriginInput.val()
        };

        this._refreshInfos(data);
    },
    updateDate: function (event) {
        if (event._refreshInfos) {
            return;
        }

        var month = this._getMonthByValue(this.monthInput.val());
        var day = this.dayInput.val();

        if (event.target == this.monthInput && day > month.nbDays) {
            day = month.nbDays;
        }

        var data = {
            'year': this.yearInput.val(),
            'month': month.number,
            'day': day,
        }

        this._refreshInfos(data);
    },
    _refreshInfos: function(data) {
        var url = this.element.data('remote');

        data.calendar = this.calendarInput.val();

        if (this.jqXHR) {
            this.jqXHR.abort();
        }

        var that = this;

        this.jqXHR = $.ajax({
            url: url,
            data: data,
            dataType: 'json',
            success: function(data) {
                that.input.val(data.text);
                that.text.html(data.text);

                that.nbDaysFromOriginInput.val(data.nbDaysFromOriginInput).trigger('change');

                that.yearInput.val(data.year);
                that.monthInput.val(data.month).trigger({ type: 'change', '_refreshInfos': true});
                that.dayInput.val(data.day);

                that.yearInput.prop('disabled', false);
                that.monthInput.prop('disabled', false);
                that.dayInput.prop('disabled', false);
            },
            complete: function(jqXHR, textStatus) {
                that.jqXHR = null;
            }
        });
    },
    _getMonthForCalendar: function(id) {
        var months = [];
        $.each(this.monthOptions, function(idx, item){
            if (item.relatedCalendar == id) {
                months.push(new Option(item.text, item.value));
            }
        });

        return months;
    },
    _getMonthByValue: function(value) {
        var m = null;

        $.each(this.monthOptions, function(idx, item){
            if (item.value == value) {
                m = item;
            }
        });

        return m;
    }
});