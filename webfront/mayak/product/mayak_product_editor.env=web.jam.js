this.$mayak_product_onSave=
$jin_eventProof( function( $mayak_product_onSave, event ){
    $mayak_product_onSave.type= 'mayak_product_onSave'
    $mayak_product_onSave.bubbles= true
})

this.$mayak_product_editor= $jin_wrapper( function( $mayak_product_editor, editor ){
    
    editor.buttons= $jin_subElement( 'mayak_button' )
    
    var init= editor.init
    editor.init= function( editor, node ){
        init.apply( this, arguments )
        
        $jin_onSubmit.listen( editor.$, function( event ){
            event.catched( true )
            $mayak_product_onSave().scream( editor.$ )
        } )
        
        editor.buttons(0).removeAttribute( 'disabled' )
        
    }
    
})

$jin_component( 'mayak_product_editor', $mayak_product_editor )