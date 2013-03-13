this.$lh_widget= $jin_class_scheme( function( $lh_widget, widget ){
    
    $jin_widget( $lh_widget )
    
    widget.buttonSubmit= function( widget ){
        return widget.$.querySelector( '*[type="submit"]' )
    }
    
    widget.errors= function( widget, errors ){
        errors.select( 'form[ errors/entry ]' )
        .forEach( function( error ){
            widget.$.elements[ error.attr( 'name' ) ]
            .setCustomValidity( error.text() )
        })
        
        widget.buttonSubmit().click()
    }
    
})