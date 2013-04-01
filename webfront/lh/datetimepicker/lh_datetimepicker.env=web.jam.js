this.$lh_datetimepicker= function(obj, currentTime) {

    var jqObj = $jq(obj);

    jqObj.mask('99.99.9999 99:99');

    var onClose= function(dateText, datepicker) {

        var addZero = function(i) {
            return ((i < 10) ? '0' : '') + i;
        }

        var date = $(this).datetimepicker('getDate')
        var formatedDate = '';
        formatedDate += $jq.datepicker.formatDate('dd.mm.yy', date);
        formatedDate += ' '
        formatedDate += addZero(date.getHours()) + ':' + addZero(date.getMinutes());
        if (formatedDate != dateText) {
            this.setCustomValidity('Вы ввели неверную дату')
            $jin_onInvalid().scream(this)
        } else {
            $jin_onChange().scream(this)
        }
    }

    var options = {
        controlType: $lh_datetimepicker_control,
        onClose: onClose
    }

    jqObj.datetimepicker(options);

    var currentTime = currentTime || false;

    if (currentTime) {
        jqObj.datetimepicker('setDate', new Date())
    }
}

$jq.timepicker.setDefaults($lh_datepicker_locale.ru);