var specs = Object.keys(window.__karma__.files).filter(function (file) {
    return /spec\.js$/.test(file);
});

require({
    baseUrl: '/base/web',
    deps: specs,
    callback: window.__karma__.start
});