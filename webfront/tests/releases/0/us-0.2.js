casper.test.begin('us-0.2', function(test) {

    casper.start("http://borovin.lighthouse.pro", function() {
        test.assertTitle("Dreamkas", "title is ok")
    });

    casper.run(function() {
        test.done();
    });
});