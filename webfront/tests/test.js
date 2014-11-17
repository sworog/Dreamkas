var phantomcss = require('phantomcss');

phantomcss.init({
    libraryRoot: './node_modules/phantomcss',
    screenshotRoot: './screenshots'
}/*{

 failedComparisonsRoot: '/failures'
 casper: specific_instance_of_casper,

 fileNameGetter: function overide_file_naming(){},
 onPass: function passCallback(){},
 onFail: function failCallback(){},
 onTimeout: function timeoutCallback(){},
 onComplete: function completeCallback(){},
 hideElements: '#thing.selector',
 addLabelToFailedImage: true,
 outputSettings: {
 errorColor: {
 red: 255,
 green: 255,
 blue: 0
 },
 errorType: 'movement',
 transparency: 0.3
 }
 }*/);

casper
    .start('http://borovin.lighthouse.pro/signup')
    .viewport(1024, 768)
    .waitForSelector('body[status="loaded"]', function() {
        phantomcss.screenshot('body', 'full body');
    })
    .then(function() {
        phantomcss.compareAll();
    });

casper.run();