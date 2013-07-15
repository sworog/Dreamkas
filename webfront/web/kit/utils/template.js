define(function(require) {
    //requirements
    require('kit/utils/text');
    require('kit/utils/attr');

    var settings = {
        evaluate: /<%([\s\S]+?)%>/g,
        interpolate: /<%=([\s\S]+?)%>/g,
        text: /<%t([\s\S]+?)%>/g,
        attr: /<%a([\s\S]+?)%>/g,
        escape: /<%e([\s\S]+?)%>/g
    };

    /**
     * JavaScript micro-templating, similar to John Resig's implementation.
     * Underscore templating handles arbitrary delimiters, preserves whitespace,
     * and correctly escapes quotes within interpolated code.
     */
    return function(str, data) {

        return 'var __p=[],print=function(){__p.push.apply(__p,arguments);};' +
            'with(obj||{}){__p.push(\'' +
            str.replace(/\\/g, '\\\\')
                .replace(/'/g, "\\'")
                .replace(settings.interpolate, function(match, code) {
                    return "'," + code.replace(/\\'/g, "'") + ",'";
                })
                .replace(settings.escape, function(match, code) {
                    return "',_.escape(" + code.replace(/\\'/g, "'") + "),'";
                })
                .replace(settings.text, function(match, code) {
                    return "',KIT.text(" + code.replace(/\\'/g, "'") + "),'";
                })
                .replace(settings.attr, function(match, code) {
                    code = $.trim(code);

                    var list = code.split('.'),
                        model = list[0],
                        attr = list[1];

                    return "',KIT.attr(" + model.replace(/\\'/g, "'") + ", '" + attr.replace(/\\'/g, "'") + "'),'";
                })
                .replace(settings.evaluate || null, function(match, code) {
                    return "');" + code.replace(/\\'/g, "'")
                        .replace(/[\r\n\t]/g, ' ') + "; __p.push('";
                })
                .replace(/\r/g, '')
                .replace(/\n/g, '')
                .replace(/\t/g, '')
            + "');}return __p.join('');";

        /** /
         var func = new Function('obj', tmpl);
         return data ? func(data) : func;
         /**/
    };
});