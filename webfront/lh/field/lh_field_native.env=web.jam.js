this.$lh_field_native= $jin_class( function( $lh_field_native, field ){

    $jin_widget( $lh_field_native )
    
    $lh_field_native.id= 'lh_field_native'

    var init= field.init
    field.init= function( field, node ){
        init.apply( this, arguments )
        
        $jin_onInput.listen( field.$, function( event ){
            field.$.setCustomValidity( '' )
        })
        
    }
    
})
