$jam_Component
(   'wc_net-bridge'
,   function( nodeRoot ){
        nodeRoot= $jam_Node( nodeRoot )
        nodeRoot.listen
        (   '$jam_eventEdit'
        ,   function( ){
                var text= $jam_html2text( nodeRoot.html() )
                nodeRoot.state( 'modified', text !== textLast )
            }
        )
        
        nodeRoot.listen
        (   '$jam_eventEdit'
        ,   $jam_Throttler
            (   5000
            ,   save
            )
        )
        
        nodeRoot.listen
        (   '$jam_eventCommit'
        ,   save
        )
        
        var textLast= $jam_html2text( nodeRoot.html() )
        function save( ){
            var text= $jam_html2text( nodeRoot.html() )
            if( text === textLast ) return
            
            var xhr= new XMLHttpRequest
            xhr.open( 'POST' , nodeRoot.attr( 'wc_net-bridge_resource' ) )
            xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
            xhr.send( nodeRoot.attr( 'wc_net-bridge_field' ) + '=' + encodeURIComponent( text ) )
            textLast= text
            nodeRoot.state( 'modified', false )
        }
        
        return new function( ){
        }
    }
)
