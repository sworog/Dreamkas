this.$lh_datetimepicker= function(obj, currentTime) {

    var jqObj = $jq(obj);

    jqObj.mask('99.99.9999 99:99');

    var onClose= function(dateText, datepicker) {
        $jq(this).val(dateText);
        $jin_onChange().scream(this)
    }

    var options = {
        controlType: $lh_datetimepicker_control,
        onClose: onClose,
        dateFormat: $lh_datepicker_settings.dateFormat,
        timeFormat: $lh_datepicker_settings.timeFormat
    }

    jqObj.datetimepicker(options);

    var currentTime = currentTime || false;

    if (currentTime) {
        jqObj.datetimepicker('setDate', new Date())
    }
}

$jq.timepicker.setDefaults($lh_datepicker_locale.ru);