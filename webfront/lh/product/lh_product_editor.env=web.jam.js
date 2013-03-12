this.$lh_product_editor= $jin_class( function( $lh_product_editor, editor ){

    var make= $lh_product_editor.make
    $lh_product_editor.make= function( node ){
        var widgets= $jin_component.widgetsOf( node )
        return widgets[ 'lh_product_editor' ] || make.apply( this, arguments )
    }
    
    $jin_widget( $lh_product_editor )
    
    $lh_product_editor.id= 'lh_product_editor'

    editor.buttons= $jin_subElement( 'lh_button' )
    
    var init= editor.init
    editor.init= function( editor, node ){
        init.apply( this, arguments )
        
        $jin_onSubmit.listen( editor.$, function( event ){
            event.catched( true )
            $lh_product_onSave().scream( editor.$ )
        } )
        
        editor.buttons(0).removeAttribute( 'disabled' )
    }
    
    editor.data= function( editor ){
        var data= $jin_domx.parse( '<product/>' )
        
        var fields= editor.$.elements
        for( var i= 0; i < fields.length; ++i ){
            var field= fields[ i ]
            if( !field.name ) continue
            
            var elem= data.$.createElement( field.name )
            elem.appendChild( data.$.createTextNode( field.value ) )
            data.$.documentElement.appendChild( elem )
        }
        
        return data
    }
    
})
