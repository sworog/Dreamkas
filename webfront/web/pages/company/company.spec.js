define(function(require, exports, module) {
    //requirements
    var Page = require('./company'),
        $ = require('jquery');

    var sharedSpecs = {
        globalNavigation: require('blocks/globalNavigation/globalNavigation.spec'),
        localNavigation_company: require('blocks/localNavigation/localNavigation_company.spec')
    };

    describe(module.id, function(){

        var page;

        beforeEach(function(done){

            page = new Page({
                fetch: function(){
                    this.collections.organizations.reset(require('collections/organizations/organizations.mock'));
                }
            });

            page.on('loaded', function(){
                done();
            });

        });

        afterEach(function(){
            page.destroy();
        });

        sharedSpecs.globalNavigation();
        sharedSpecs.localNavigation_company();

        it('organization links', function(){

            var $links = $('[name="organizationLink"]');

            expect($links.length).toEqual(3);

            $links.each(function(index, link){
                expect($(link).attr('href')).toEqual('/company/organizations/' + page.collections.organizations.at(index).id);
            });
        });

    });
});