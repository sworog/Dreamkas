void function( ){
    
    var nodeSummary= $jam_Lazy( function( ){
        return $jam_Value( $jam_Node.parse( '<a wc_test_summary="true" />' ).parent( $jam_body() ) )
    } )
    
    var screamResults= function( passed ){
        //$jam_Event().type( 'wc_test_done' ).scream( nodeSummary() )
        $testo_slave.done( passed )
    }
    
    var refreshSummary= $jam_Throttler( 50, function( ){
        var nodes= $jam_Node( document ).descList( 'script' )
        for( var i= 0; i < nodes.length(); ++i ){
            var node= nodes.get( i )
            switch( node.attr( 'wc_test_passed' ) ){
                case 'true':
                    break
                case 'false':
                    nodeSummary().attr( 'wc_test_passed', 'false' )
                    while( node && !node.attr( 'id' ) ) node= node.parent()
                    if( node ) nodeSummary().attr( 'href', node.attr( 'id' ) )
                    screamResults( false )
                case 'wait':
                    return
            }
        }
        nodeSummary().attr( 'wc_test_passed', 'true' )
        nodeSummary().attr( 'href', '' )
        screamResults( true )
    } )
    
    $jam_Component
    (   'script'
    ,   function( nodeRoot ){
            nodeRoot= $jam_Node( nodeRoot )
            if( nodeRoot.attr( 'type' ) !== 'wc_test' ) return null
            
            return new function( ){
                
                var source= $jam_String( nodeRoot.html() ).minimizeIndent().trim( /[\n\r]/ ).$
                
                nodeRoot.clear()
                var nodeSource0= $jam_Node.Element( 'wc_test_source' ).parent( nodeRoot )
                var nodeSource= $jam_Node.parse( '<wc_editor wc_editor_hlight="js" />' ).text( source ).parent( nodeSource0 )
                var nodeLogs= $jam_Node.Element( 'wc_test_logs' ).parent( nodeRoot )
                var nodeControls= $jam_Node.Element( 'wc_hontrol' ).parent( nodeRoot )
                var nodeClone= $jam_Node.parse( '<wc_hontrol_clone title="ctrl+shift+enter" />' ).text( 'clone' ).parent( nodeControls )
                var nodeDelete= $jam_Node.parse( '<wc_hontrol_delete/>' ).text( 'delete' ).parent( nodeControls )
                
                var checkDone= function( ){
                    refreshSummary()
                    if( passed() === 'wait' ) return
                    passed( false )
                    throw new Error( 'Test already done' )
                }
                
                var stop
                
                var passed=
                $jam_Poly
                (   function( ){
                        return nodeRoot.attr( 'wc_test_passed' )
                    }
                ,   function( val ){
                        nodeRoot.attr( 'wc_test_passed', val )
                    }
                )
                
                var printError=
                function( val ){
                    var node= $jam_Node.Element( 'wc_test_error' )
                    node.text( val )
                    nodeLogs.tail( node )
                }
                
                var dumpValue=
                function( val ){
                    if( typeof val === 'function' ){
                        if( !val.hasOwnProperty( 'toString' ) ){
                            return 'Function: [object Function]'
                        }
                    }
                    return $jam_classOf( val ) + ': ' + val
                }
                
                var printResults=
                function( list ){
                    var node= $jam_Node.Element( 'wc_test_results' )
                    for( var j= 0; j < list.length; ++j ){
                        var val= $jam_Node.Element( 'wc_test_results_value' )
                        val.text( dumpValue( list[ j ] ) )
                        node.tail( val )
                    }
                    nodeLogs.tail( node )
                }
                
                var run=
                function( ){
                    nodeLogs.clear()
                    passed( 'wait' )
                    $jin_test( nodeSource.text(), update )
                }
                
                var update=
                function( test ){
                    passed( test.passed )
                    for( var i= 0; i < test.results.length; ++i ){
                        printResults( test.results[ i ] )
                    }
                    for( var i= 0; i < test.errors.length; ++i ){
                        printError( test.errors[ i ] )
                    }
                    refreshSummary()
                }
                
                var clone=
                function( ){
                    run()
                    var node=
                    $jam_Node.Element( 'script' ).attr( 'type', 'wc_test' )
                    .text( nodeSource.text() )
                    nodeRoot.prev( node )
                }
                
                var del=
                function( ){
                    nodeRoot.parent( null )
                }
                
                run()
                
                var onCommit=
                nodeRoot.listen( '$jam_eventCommit', run )
                
                var onClone=
                nodeRoot.listen( '$jam_eventClone', clone )
                
                var onClone=
                nodeRoot.listen( '$jam_eventDelete', del )
                
                var onCloneClick=
                nodeClone.listen( 'click', function( event ){
                    $jam_Event().type( '$jam_eventClone' ).scream( event.target() )
                })
                
                var onDeleteClick=
                nodeDelete.listen( 'click', function( event ){
                    $jam_Event().type( '$jam_eventDelete' ).scream( event.target() )
                })
                
                this.destroy=
                function( ){
                    onCommit.sleep()
                    onClone.sleep()
                    onCloneClick.sleep()
                    onDeleteClick.sleep()
                    if( stop ) stop()
                    refreshSummary()
                }
                
            }
        }
    )
}()