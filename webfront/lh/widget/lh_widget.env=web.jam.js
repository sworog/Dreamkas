this.$lh_widget= $jin_class_scheme( function( $lh_widget, widget ){
    
    $jin_widget( $lh_widget )
    
    widget.buttonSubmit= function( widget ){
        return widget.$.querySelector( '*[type="submit"]' )
    }
    
    widget.data= function( widget ){
        var data= $jin_domx.parse( '<data/>' )
        
        var stack= [ data ]
        var field= widget.$.firstChild
        
        traverse: while( true ){
            var name= field.getAttribute && field.getAttribute( 'name' )
            if( name ){
                var value= field.value || ''
                var last= stack[ stack.length - 1 ]
                var node= data.Element( name ).text( value ).parent( last )
                stack.push( node )
            }
            if( field.firstChild ){
                field= field.firstChild
            } else {
                if( field.name ) stack.pop()
                while( !field.nextSibling ){
                    field= field.parentNode
                    if( field.name ) stack.pop()
                    if( field === widget.$ ) break traverse
                }
                field= field.nextSibling
            }
        }
        
        return data.select( '*' )[ 0 ]
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