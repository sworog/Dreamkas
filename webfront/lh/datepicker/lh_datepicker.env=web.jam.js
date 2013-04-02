$jq_ui_timepicker;

this.$lh_datepicker= function(obj, currentTime) {

    var jqObj = $jq(obj);

    jqObj.mask('99.99.9999');

    var onClose = function(dateText, datepicker) {
        $jin_onChange().scream(this);
    }

    var options = {
        dateFormat: $lh_datepicker_settings.dateFormat,
        onClose: onClose
    }

    jqObj.datepicker(options);

    var currentTime = currentTime || false;

    if (currentTime) {
        jqObj.datepicker('setDate', new Date())
    }
}