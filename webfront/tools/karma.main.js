var specFiles = Object.keys(window.__karma__.files).filter(function (file) {
    return /\.spec\.js$/.test(file);
});

var specModules = specFiles.map(function(moduleUrl){
    return moduleUrl
        .replace(/\/base\//g, '')
        .replace(/.js/g, '');
});

require(specModules, window.__karma__.start);