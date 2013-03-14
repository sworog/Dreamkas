this.$lh_product_edit= $jin_class( function( $lh_product_edit, editor ){

    $lh_widget( $lh_product_edit )
    
    $lh_product_edit.id= 'lh_product_edit'
    
    var init= editor.init
    editor.init= function( editor, node ){
        init.apply( this, arguments )
        
        $jin_onSubmit.listen( editor.$, function( event ){
            event.catched( true )
            $lh_product_onSave().scream( editor.$ )
        } )
        
        editor.buttonSubmit().removeAttribute( 'disabled' )
    }
    
})
