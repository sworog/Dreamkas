window.Helpers = {
    pricesFloatToView: function(price) {
        return price.toString().replace(".", ",");
    },

    dateTimeFormat: function(date) {
        return date.replace(/(\d+)\-(\d+)\-(\d+)T(\d+):(\d+).*/, "$3.$2.$1 $4:$5");
    },

    dateFormat: function(date) {
        return date.replace(/(\d+)\-(\d+)\-(\d+)T(\d+):(\d+).*/, "$3.$2.$1");
    }
};