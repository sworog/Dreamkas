casper.test.begin('us-0.1', function(test) {

    casper.start("http://borovin.lighthouse.pro", function() {
        test.assertTitle("Dreamkas", "title is ok")
    });

    casper.run(function() {
        test.done();
    });
});