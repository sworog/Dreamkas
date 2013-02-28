$jam_Component
(   'wc_demo'
,   function( nodeRoot ){
        return new function( ){
            nodeRoot= $jam_Node( nodeRoot )

            var source= $jam_String( nodeRoot.text() ).minimizeIndent().trim( /[\n\r]/ ).$
            
            nodeRoot.clear()
            
            var nodeResult=
            $jam_Node.Element( 'wc_demo_result' )
            .parent( nodeRoot )
            
            var nodeSource0=
            $jam_Node.Element( 'wc_demo_source' )
            .parent( nodeRoot )
            
            var nodeSource=
            $jam_Node.parse( '<wc_editor wc_editor_hlight="sgml" />' )
            .text( source )
            .parent( nodeSource0 )
            
            var exec= $jin_thread( function( ){
                var source= $jam_String( nodeSource.text() ).minimizeIndent().trim( /[\n\r]/ )
                //nodeSource.text( source )
                nodeResult.html( source )
                var scripts= nodeResult.descList( 'script' )
                for( var i= 0; i < scripts.length; ++i ){
                    var script= $jam_Node( scripts[i] )
                    $jam_eval( script.text() )
                }
                return true
            })
            
            exec()
        
            var onCommit=
            nodeSource.listen( '$jam_eventCommit', exec )
            
            this.destroy=
            function( ){
                onCommit.sleep()
            }
        }
    }
)
