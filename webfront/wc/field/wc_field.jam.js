$jam_Component
(   'wc_field'
,   function( nodeRoot ){
        nodeRoot= $jam_Node( nodeRoot )
        
        var nodeInput=
        $jam_Node.Element( 'input' )
        .attr( 'type', 'hidden' )
        .attr( 'name', nodeRoot.attr( 'wc_field_name' ) )
        .parent( nodeRoot )
        
        nodeRoot.listen
        (   '$jam_eventEdit'
        ,   sync
        )
        
        var onEdit=
        nodeRoot.listen
        (   '$jam_eventEdit'
        ,   sync
        )
        
        function sync( ){
            var text= $jam_html2text( nodeRoot.html() ).replace( /[\n\r]+/g, '' )
            nodeInput.$.value= text
        }
        
        sync()
        
        return new function( ){
            this.destroy= function(){
                onEdit.sleep()
                nodeInput.parent( null )
            }
        }
    }
)
