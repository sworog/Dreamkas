var config= require( './testo_config.js' )

var connect = require( 'connect' )
var server= connect.createServer( connect.static( __dirname ) )
.listen
(   config.port
,   function( ){
        
        var io= require( 'socket.io' ).listen( server )
        
        var agents= {}
        var states= {}
        
        io.sockets.on( 'connection', function( socket ){
            
            socket.on( 'agent:ready', function( param ){
                console.log( 'agent: ' + param.id )
                agents[ param.id ]= socket
            } )
            
            socket.on( 'test:run', function( param ){
                states= {}
                socket.broadcast.emit( 'agent:run', { uri: config.uri.replace( '{testo_session}', param.id ) })
                setTimeout( terminate, 30000 )
            } )
            
            socket.on( 'agent:done', function( param ){
                states[ param.id ]= param.state
                console.log( param.state + ':' + param.id )
                for( var id in agents ){
                    if(!( id in states )) return
                }
                console.log( 'done: ' + JSON.stringify( states ) )
                socket.broadcast.emit( 'test:done', states )
            } )
            
            function terminate( ){
                console.log( 'timeout!' )
                for( var id in agents ){
                    states[ id ]= states[ id ]
                }
                socket.broadcast.emit( 'test:done', states )
            }
            
        } )
        
        void ( config.browsers || [] ).forEach( persistBrowser )
        
        function persistBrowser( path ){
            console.log( 'start: ' + path )
            require( 'child_process' ).execFile
            (   path
            ,   [ 'http://' + config.host + ':' + config.port + '/testo_base.html' ]
            ,   {}
            ,   function( error, stdout, stderr ){
                    onExit()
                }
            )
            
            var onExit= function( ){ }
            
            setTimeout( function( ){
                onExit= function( ){
                    persistBrowser( path )
                }
            }, 1000 )
        }

    }
)
