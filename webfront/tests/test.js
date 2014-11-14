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

casper.start( 'http://lighthouse.dev/signup' );

casper.viewport(1024, 768);

casper.waitForSelector('body[status="loaded"]', function() {
    phantomcss.screenshot('body', 'full body');
});

casper.then( function(){
    // compare screenshots
    phantomcss.compareAll();
});

casper.then( function(){
    casper.test.done();
});

/*
 Casper runs tests
 */
casper.run(function(){
    console.log('\nTHE END.');
    phantom.exit(phantomcss.getExitStatus());
});