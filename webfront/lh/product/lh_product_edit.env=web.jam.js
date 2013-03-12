this.$lh_product_edit= $jin_class( function( $lh_product_edit, editor ){

    $jin_widget( $lh_product_edit )
    
    $lh_product_edit.id= 'lh_product_edit'

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
            
            data.Element( field.name )
            .text( field.value )
            .parent( data )
        }
        
        return data
    }
    
})
