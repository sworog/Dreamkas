this.$lh_acceptance_edit= $jin_class( function( $lh_acceptance_edit, editor ){

    $lh_widget( $lh_acceptance_edit )
    
    $lh_acceptance_edit.id= 'lh_acceptance_edit'
    
    var init= editor.init
    editor.init= function( editor, node ){
        init.apply( this, arguments )
        
        $jin_onSubmit.listen( editor.$, function( event ){
            event.catched( true )
            $lh_acceptance_onSave().scream( editor.$ )
        } )
        
        editor.buttonSubmit().removeAttribute( 'disabled' )
    }
    
})
