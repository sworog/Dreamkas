this.$mayak_product_onSave=
$jin_eventProof( function( $mayak_product_onSave, event ){
    $mayak_product_onSave.type= 'mayak_product_onSave'
    $mayak_product_onSave.bubbles= true
})

this.$mayak_product_editor= $jin_wrapper( function( $mayak_product_editor, editor ){
    
    var init= editor.init
    editor.init= function( editor, node ){
        init.apply( this, arguments )
        
        $jq( editor.$ ).on( 'submit', function( event ){
            event.preventDefault()
            $mayak_product_onSave().scream( editor.$ )
        })
        
    }
    
})

$jin_component( 'mayak_product_editor', $mayak_product_editor )
