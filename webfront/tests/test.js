casper.test.begin("Hello, Test!", 1, function(test) {

    casper.start('http://lighthouse.dev', function() {
        var title = this.getTitle();

        test.assertEquals(title, 'Dreamkas');
        test.done();
    });

    casper.run();
});