this.$lh_form= $jin_class( function( $lh_form, form ){
    
    $jin_wrapper.scheme( $lh_form )
    
    form.buttonSubmit= function( form ){
        return form.$.querySelector( '*[type="submit"]' )
    }
    
    form.data= function( form ){
        var data= $jin_domx.parse( '<data/>' )
        
        var stack= [ data ]
        var field= form.$.firstChild
        
        traverse: while( true ){
            var name= field.getAttribute && field.getAttribute( 'name' )
            if( name ){
                var value= field.value || field.innerText || ''
                var last= stack[ stack.length - 1 ]
                var node= data.Element( name ).text( value ).parent( last )
            }
            if( field.firstChild ){
                field= field.firstChild
                if( name ) stack.push( node )
            } else {
                while( !field.nextSibling ){
                    field= field.parentNode
                    var name= field.getAttribute && field.getAttribute( 'name' )
                    if( name ) stack.pop()
                    if( field === form.$ ) break traverse
                }
                field= field.nextSibling
            }
        }
        
        // TODO: не пихать лишние узлы, чтобы не требовалось вырезать
        data.select( ' // text()[ .. / * ] ' )
        .forEach( function( text ){
            text.parent( null )
        } )
        
        return data.select( '*' )[ 0 ]
    }
    
    form.errors= function( form, errors ){
        
        errors.select( 'form[ errors/entry ]' )
        .forEach( function( error ){
            form.$.elements[ error.attr( 'name' ) ]
            .setCustomValidity( error.text() )
        })
        
        form.buttonSubmit().click()
    }
    
})