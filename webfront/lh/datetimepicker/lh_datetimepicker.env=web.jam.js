this.$lh_datetimepicker= function(obj, currentTime) {

    var jqObj = $jq(obj);

    jqObj.mask('99.99.9999 99:99');

    var onClose= function(dateText, datepicker) {

        var addZero = function(i) {
            return ((i < 10) ? '0' : '') + i;
        }

        var formatDate = function(date) {
            var formattedDate = $jq.datepicker.formatDate('dd.mm.yy', date);
            formattedDate += ' '
            formattedDate += addZero(date.getHours()) + ':' + addZero(date.getMinutes());
            return formattedDate;
        }

        var date = $(this).datetimepicker('getDate')
        var formattedDate = formatDate(date);

        if (formattedDate != dateText) {
            this.setCustomValidity('Вы ввели неверную дату')
            $jin_onInvalid().scream(this)
        } else {
            $lh_onValid().scream(this)
            $jin_onChange().scream(this)
        }
    }

    var options = {
        controlType: $lh_datetimepicker_control,
        onClose: onClose,
        dateFormat: 'dd.mm.yy',
        timeFormat: 'HH:mm'
    }

    jqObj.datetimepicker(options);

    var currentTime = currentTime || false;

    if (currentTime) {
        jqObj.datetimepicker('setDate', new Date())
    }
}

$jq.timepicker.setDefaults($lh_datepicker_locale.ru);