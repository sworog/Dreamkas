define(function(require) {
    //requirements

    return function(deps, getter){
        getter.dependencies = deps;
        return getter;
    }
});