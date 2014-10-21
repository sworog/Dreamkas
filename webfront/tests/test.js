casper.test.begin('Dreamkas', function suite(test) {

    casper.start("http://borovin.lighthouse.pro", function() {
        test.assertTitle("Dreamkas", "title is ok")
    });

    casper.run(function() {
        test.done();
    });
});