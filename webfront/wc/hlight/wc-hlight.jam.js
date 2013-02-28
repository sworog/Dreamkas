$jam_Component
(   'wc_hlight'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $jam_Node( nodeRoot )

            var hlight= $lang( nodeRoot.attr( 'wc_hlight_lang' ) )
            var source= $jam_String( nodeRoot.text() ).minimizeIndent().trim( /[\r\n]/ ).$

            nodeRoot
            .html( hlight( source ) )
            
        }
    }
)
