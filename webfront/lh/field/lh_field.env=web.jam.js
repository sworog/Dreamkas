this.$lh_field= $jin_class( function( $lh_field, field ){

    $jin_widget( $lh_field )
    
    $lh_field.id= 'lh_field'
    
    field.onInvalid= null
    field.onChange= null
    
    field.native= function( field ){
        return field.$.querySelector( 'input, select, textarea' )
    }
    
    var init= field.init
    field.init= function( field, node ){
        init.apply( this, arguments )
        
        var native= field.native()
        
        if( !native ) return
        
        field.onInvalid=
        $jin_onInvalid.listen( native, function( event ){
            field.$.setAttribute( 'lh_field_error', field.native().validationMessage )
            event.catched( true )
        })
        
        field.onChange=
        $jin_onChange.listen( native, function( event ){
            field.$.removeAttribute( 'lh_field_error' )
            field.native().setCustomValidity( '' )
            field.native().checkValidity()
        })
        
    }
    
})
