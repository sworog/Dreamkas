void function( ){
    var nodeSummary= $jam_Lazy( function( ){
        return $jam_Value( $jam_Node.parse( '<a wc_js-test_summary="true" />' ).parent( $jam_body() ) )
    } )
    
    var refreshSummary= $jam_Throttler( 50, function( ){
        var nodes= $jam_Node( document ).descList( 'wc_js-test' )
        for( var i= 0; i < nodes.length(); ++i ){
            var node= nodes.get( i )
            switch( node.attr( 'wc_js-test_passed' ) ){
                case 'true':
                    break
                case 'false':
                    nodeSummary().attr( 'wc_js-test_passed', 'false' )
                    while( node && !node.attr( 'id' ) ) node= node.parent()
                    if( node ) nodeSummary().attr( 'href', '#' + node.attr( 'id' ) )
                case 'wait':
                    return
            }
        }
        nodeSummary().attr( 'wc_js-test_passed', 'true' )
        nodeSummary().attr( 'href', '' )
    } )

    $jam_Component
    (   'wc_js-test'
    ,   function( nodeRoot ){
            return new function( ){
                nodeRoot= $jam_Node( nodeRoot )
                
                var exec= $jin_thread( function( ){
                    var source= nodeSource.text()
                    var proc= new Function( '_test', source )
                    proc( _test )
                    return true
                })
                
                var source= $jam_String( nodeRoot.text() ).minimizeIndent().trim( /[\n\r]/ ).$
            
                nodeRoot.clear()
                var nodeSource0= $jam_Node.Element( 'wc_js-test_source' ).parent( nodeRoot )
                var nodeSource= $jam_Node.parse( '<wc_editor wc_editor_hlight="js" />' ).text( source ).parent( nodeSource0 )
                var nodeControls= $jam_Node.Element( 'wc_hontrol' ).parent( nodeRoot )
                var nodeClone= $jam_Node.parse( '<wc_hontrol_clone title="ctrl+shift+enter">clone' ).parent( nodeControls )
                var nodeDelete= $jam_Node.parse( '<wc_hontrol_delete>delete' ).parent( nodeControls )
                
                var _test= {}
                
                var checkDone= function( ){
                    refreshSummary()
                    if( passed() === 'wait' ) return
                    passed( false )
                    throw new Error( 'Test already done' )
                }
                
                _test.ok=
                $jam_Poly
                (   function( ){
                        checkDone()
                        if( passed() === 'wait' ) passed( true )
                    }
                ,   function( val ){
                        checkDone()
                        passed( Boolean( val ) )
                        printValue( val )
                        if( !val ) throw new Error( 'Result is empty' )
                    }
                ,   function( a, b ){
                        checkDone()
                        passed( a === b )
                        printValue( a )
                        if( a !== b ){
                            printValue( b )
                            throw new Error( 'Results is not equal' )
                        }
                    }
                ,   function( a, b, c ){
                        checkDone()
                        passed(( a === b )&&( a === c ))
                        printValue( a )
                        if(( a !== b )||( a !== c )){
                            printValue( b )
                            printValue( c )
                            throw new Error( 'Results is not equal' )
                        }
                    }
                )
    
                _test.not=
                $jam_Poly
                (   function( ){
                        checkDone()
                        passed( false )
                        throw new Error( 'Test fails' )
                    }
                ,   function( val ){
                        checkDone()
                        printValue( val )
                        passed( !val )
                        if( val ) throw new Error( 'Result is not empty' )
                    }
                ,   function( a, b ){
                        checkDone()
                        printValue( a )
                        printValue( b )
                        passed( a !== b )
                        if( a == b ) throw new Error( 'Results is equal' )
                    }
                )
                
                var stop
                
                var noMoreWait= function( ){
                    if( passed() !== 'wait' ) return
                    refreshSummary()
                    passed( false )
                    print( 'Timeout!' )
                    stop= null
                    throw new Error( 'Timeout!' )
                }
                
                _test.deadline=
                $jam_Poly
                (   null
                ,   function( ms ){
                        if( stop ) throw new Error( 'Deadline redeclaration' )
                        stop= $jam_schedule( ms, noMoreWait )
                    }
                )
            
                var passed=
                $jam_Poly
                (   function( ){
                        return nodeRoot.attr( 'wc_js-test_passed' )
                    }
                ,   function( val ){
                        nodeRoot.attr( 'wc_js-test_passed', val )
                    }
                )
                
                var print=
                function( val ){
                    var node= $jam_Node.Element( 'wc_js-test_result' )
                    node.text( val )
                    nodeRoot.tail( node )
                }
                
                var printValue=
                function( val ){
                    if( typeof val === 'function' ){
                        if( !val.hasOwnProperty( 'toString' ) ){
                            print( 'Function: [object Function]' )
                            return
                        }
                    }
                    print( $jam_classOf( val ) + ': ' + val )
                }
                
                var run=
                function( ){
                    var results= nodeRoot.childList( 'wc_js-test_result' )
                    for( var i= 0; i < results.length(); ++i ){
                        results.get(i).parent( null )
                    }
                    passed( 'wait' )
                    stop= null
                    if( !exec() ) passed( false )
                    if(( !stop )&&( passed() === 'wait' )) passed( false )
                    refreshSummary()
                }
                
                var clone=
                function( ){
                    run()
                    var node=
                    $jam_Node.Element( 'wc_js-test' )
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
                    _test.ok= _test.not= $jam_Value()
                    refreshSummary()
                }
                
            }
        }
    )
}()