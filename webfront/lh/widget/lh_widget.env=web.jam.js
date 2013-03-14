this.$lh_widget= $jin_class_scheme( function( $lh_widget, widget ){
    
    $jin_widget( $lh_widget )
    
    widget.buttonSubmit= function( widget ){
        return widget.$.querySelector( '*[type="submit"]' )
    }
    
    widget.data= function( widget ){
        var data= $jin_domx.parse( '<' + widget.$.getAttribute( 'name' ) + '/>' )
        
        var fields= widget.$.querySelectorAll( '*[name]' )
        for( var i= 0; i < fields.length; ++i ){
            var field= fields[ i ]
            
            var name= field.name
            var value= field.value
            
            data.Element( name ).text( value ).parent( data )
        }
        
        return data
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