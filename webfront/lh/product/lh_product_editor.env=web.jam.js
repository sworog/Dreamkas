this.$lh_product_onSave=
$jin_eventProof( function( $lh_product_onSave, event ){
    $lh_product_onSave.type= 'lh_product_onSave'
    $lh_product_onSave.bubbles= true
})

this.$lh_product_editor= $jin_wrapper( function( $lh_product_editor, editor ){
    
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
    
})

$jin_component( 'lh_product_editor', $lh_product_editor )