var tests = Object.keys(window.__karma__.files).filter(function (file) {
    return /spec\.js$/.test(file);
});

require({
    baseUrl: '/base/web',
    deps: tests,
    callback: window.__karma__.start
});