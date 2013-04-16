define(function() {
    return function(price) {
        if (null == price) {
            price = '0,00';
        } else {
            price = price.toString().replace(".", ",");
        }
        if (-1 == price.indexOf(',')) {
            price+= ',';
        }
        price+= '00';
        price = price.replace(/(,\d{2})\d*$/, '$1');
        return price;
    }
});
