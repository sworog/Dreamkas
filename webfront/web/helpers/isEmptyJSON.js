define(function() {
    return function(data) {
        for (var item in data) {
            if ('' != data[item]) {
                return false;
            }
        }

        return true;
    }
})