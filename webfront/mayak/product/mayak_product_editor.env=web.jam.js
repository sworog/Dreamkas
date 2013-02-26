this.$mayak_product_onSave= $jin_eventProof( function( $mayak_product_onSave, event ){
    
    $mayak_product_onSave.type= 'mayak_product_onSave'
    $mayak_product_onSave.bubbles= true
    
    event.data= function( event, data ){
        if( arguments.length > 1 ){
            event.$.data= data
            return event
        } else {
            return event.$.data
        }
    }
    
    event.onDone= function( event, onDone ){
        if( arguments.length > 1 ){
            event.$.onDone= onDone
            return event
        } else {
            return event.$.onDone
        }
    }
    
})

$mayak_product_onSave.listen( document.body, function( event ){
    $jq.ajax
    (   '/'
    ,   {   type: 'post'
        ,   data: event.data()
        ,   success: function( data ){
                event.onDone()( null, data )
            }
        ,   error: function( data ){
                event.onDone()( data )
            }
        }
    )
    event.catched( true )
})

this.$mayak_product_editor= $jin_wrapper( function( $mayak_product_editor, editor ){
    
    var init= editor.init
    editor.init= function( editor, node ){
        init.apply( this, arguments )
        
        $jq( editor.$ ).on( 'submit', function( event ){
            event.preventDefault()
            console.log( $jq( editor.$ ).serialize() )
            $mayak_product_onSave()
            .data( $jq( editor.$ ).serialize() )
            .onDone( function( error, response ){
                console.log([ error, response ])
            })
            .scream( editor.$ )
        })
        
    }
    
})

$mayak_component( 'mayak_product_editor', $mayak_product_editor )
