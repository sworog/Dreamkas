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
            //field.$.removeAttribute( 'lh_field_error' )
            field.native().setCustomValidity( '' )
            field.native().checkValidity()
        })
        field.onValid=
        $lh_onValid.listen( native, function( event ){
            /*
            if (field.$.hasAttribute('lh_field_error')) {
                field.$.removeAttribute( 'lh_field_error' )
            }
            */
            field.native().setCustomValidity( '' );
            field.native().checkValidity()
            event.catched( true )
        })
        
        if( ~[ 'INPUT', 'SELECT' ].indexOf( native.nodeName ) ){
            field.onPress=
            $jin_onPress.listen( native, function( event ){
                if( event.keyCode() === $jin_keyCode.enter ){
                    var next= $jin_domx( field.$ ).select( 'following::*[@lh_field_native]' )[0]
                    if( next ){
                        next.$.focus()
                        event.catched( true )
                    }
                }
            })
        }
        
    }
    
})
