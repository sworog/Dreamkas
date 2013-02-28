$jam_Component
(   'iframe'
,   function( nodeRoot ){
        nodeRoot= $jam_Node( nodeRoot )
        if( !nodeRoot.attr( 'wc_yasearchresult' ) )
            return null
        
        return new function( ){
            var observer=
            $jam_Observer()
            .node( document )
            .eventName( '$jam_eventURIChanged' )
            .handler( function(){
                if( update() )
                    window.history.replaceState( null, null, document.location.search )
            })
            .listen()
            
            function update( ){
                var data= document.location.hash.substring(1)
                var height= parseInt( data )
                
                if( data != height )
                    return false
                
                nodeRoot.$.style.height= height + 'px'
                
                return true
            }
            
            update()
            
            this.destroy= function(){
                observer.sleep()
            }
        }
    }
)
