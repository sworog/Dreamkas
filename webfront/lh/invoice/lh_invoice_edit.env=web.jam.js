this.$lh_invoice_edit= $jin_class( function( $lh_invoice_edit, editor ){

    $lh_widget( $lh_invoice_edit )

    $lh_invoice_edit.id= 'lh_invoice_edit'
    
    var init= editor.init
    editor.init= function( editor, node ){
        init.apply( this, arguments )

        $lh_datetimepicker(editor.$.querySelector('[name="acceptanceDate"]'), true)
        $lh_datepicker(editor.$.querySelector('[name="supplierInvoiceDate"]'))
        
        $jin_onSubmit.listen( editor.$, function( event ) {
            event.catched( true )
            $lh_invoice_onSave().scream( editor.$ )
        } )
        
        editor.buttonSubmit().removeAttribute( 'disabled' )
    }
    
})
