this.$lh_datetimepicker= function(obj, currentTime) {

    var jqObj = $jq(obj);

    var onClose= function(dateText, datepicker) {
        $jin_onChange().scream(this);
    };

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