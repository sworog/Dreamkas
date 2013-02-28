$jam_Component
(   'wc_js-bench_list'
,   new function( ){
        return function( nodeRoot ){
            nodeRoot= $jam_Node( nodeRoot )
            
            var nodeHeader=
            $jam_Node.parse( '<wc_js-bench_header title="ctrl + enter" />' )
            .tail( $jam_Node.parse( '<wc_js-bench_runner>Run ►' ) )
            .tail( $jam_Node.parse( '<wc_js-bench_column>inner (µs)' ) )
            .tail( $jam_Node.parse( '<wc_js-bench_column>outer (µs)' ) )
            
            nodeRoot.head( nodeHeader )

            //var nodeControls= $jam_Node.Element( 'wc_hontrol' ).parent( nodeRoot )
            //var nodeClone= $jam_Node.parse( '<wc_hontrol_clone title="ctrl+shift+enter">clone' ).parent( nodeControls )
            //var nodeDelete= $jam_Node.parse( '<wc_hontrol_delete>delete' ).parent( nodeControls )

            var refresh=
            function( ){
                var benchList= nodeRoot.childList( 'wc_js-bench' )
                for( var i= 0; i < benchList.length(); ++i ){
                    $jam_Event()
                    .type( '$jam_eventCommit' )
                    .scream( benchList.get( i ) )
                }
            }

            var onClick=
            nodeHeader.listen( 'click', refresh )
            
            return new function( ){
                this.destroy=
                function( ){
                    onClick.sleep()
                }
            }

        }
    }
)

$jam_Component
(   'wc_js-bench'
,   new function( ){
    
        var queue=
        $jam_TaskQueue()
        .latency( 100 )
    
        var parser= /^([\s\S]*?)_bench\.begin\(\)([\s\S]*)_bench\.end\(\)([\s\S]*)$/
    
        return function( nodeRoot ){

            nodeRoot= $jam_Node( nodeRoot )
            var source= $jam_String( nodeRoot.text() ).minimizeIndent().trim( /[\r\n]/ ).$

            nodeRoot
            .clear()
            
            var nodeSource=
            $jam_Node.parse( '<wc_js-bench_source><wc_editor wc_editor_hlight="js">' + $jam_htmlEscape( source ) )
            .parent( nodeRoot )
            
            var nodeInner=
            $jam_Node.parse( '<wc_js-bench_result class=" source=inner " />' )
            .parent( nodeRoot )

            var nodeOuter=
            $jam_Node.parse( '<wc_js-bench_result class=" source=outer " />' )
            .parent( nodeRoot )
            
            nodeRoot.surround( $jam_Node.Fragment() ) // for chrome 12
            
            var calc= $jin_thread( function( source ){
                var startCompile= new Date
                    var proc= new Function( '', source )
                var endCompile= new Date
                var startExec= new Date
                    proc()
                var endExec= new Date
                return new function( ){
                    this.compile= endCompile.getTime() - startCompile.getTime()
                    this.exec= endExec.getTime() - startExec.getTime()
                }
            })

            var format= function( time ){
                return time.toFixed( 3 )
            }

            var run=
            function( ){
                var source= nodeSource.text()
                var matches= parser.exec( source )
                if( matches ){
                    var prefix= matches[1] + ';'
                    var sourceInner= matches[2] + ';'
                    var postfix= matches[3] + ';'
                } else {
                    var prefix= ''
                    var sourceInner= source + ';'
                    var postfix= ''
                }

                var count= 1
                var sourceOuter= prefix + postfix
                if( sourceOuter ){
                    do {
                        sourceOuter+= sourceOuter

                        var time= calc( sourceOuter )
                        if( !time ) break
                        var timeOuter= time
                        count*= 2

                        if( timeOuter.compile > 256 ) break
                        if( timeOuter.exec > 256 ) break
                    } while( true )

                    if( !timeOuter ) timeOuter= {}
                    timeOuter.compile= timeOuter.compile * 1000 / count
                    timeOuter.exec= timeOuter.exec * 1000 / count
                } else {
                    timeOuter= { compile: 0, exec: 0 }
                }
                
                nodeOuter
                .text( format( timeOuter.exec ) )
                .attr( 'title', 'compile: ' + format( timeOuter.compile ) + ' / ' + count )

                var count= 1
                do {
                    sourceInner+= sourceInner

                    var time= calc( prefix + sourceInner + postfix )
                    if( !time ) break
                    var timeInner= time
                    count*= 2

                    if( timeInner.compile > 256 ) break
                    if( timeInner.exec > 256 ) break
                } while( true )
                
                if( !timeInner ) timeInner= {}
                timeInner.compile= ( timeInner.compile * 1000 - timeOuter.compile ) / count
                timeInner.exec= ( timeInner.exec * 1000 - timeOuter.exec ) / count
                
                nodeInner
                .text( format( timeInner.exec ) )
                .attr( 'title', 'compile: ' + format( timeInner.compile ) + ' / ' + count )

                nodeRoot.state( 'wait', 'false' )
            }
            
            var schedule=
            function( ){
                if( nodeRoot.state( 'wait' ) === 'true' ) return 
                queue.add( run )
                nodeRoot.state( 'wait', 'true' )
            }
            
            var clone=
            function( ){
                var node=
                $jam_Node.Element( 'wc_js-bench' )
                .text( nodeSource.text() )
                nodeRoot.prev( node )
            }
            
            var onCommit=
            nodeRoot.listen( '$jam_eventCommit', schedule )
            
            var onClone=
            nodeRoot.listen( '$jam_eventClone', clone )
            
            return new function( ){
                this.destroy=
                function( ){
                    onCommit.sleep()
                    onClone.sleep()
                }
            }

        }
    }
)
