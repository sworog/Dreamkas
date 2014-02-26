define(function(require) {
    //requirements

    return Page.extend({
        partials: {
            '#globalNavigation': function() {
                return globalNavigation({currentUserModel: currentUserModel});
            },
            '#localNavigation': null,
            '#content': null
        }
    });
});