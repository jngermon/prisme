require('./vendor');
require('./widget/calendar_date');

$(document).ready(function() {

    $('.sonata_dtp').each(function(idx, dtp){
        $(dtp).datetimepicker($(dtp).data('options'));
    });

    $('.calendar_date').calendarDate();
});
