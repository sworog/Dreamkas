var config= require( './testo_config.js' )

var log= function( message ){
    require( 'fs' ).appendFileSync( 'testo_server.log', message + '\n' )
}

var connect = require( 'connect' )
var server= connect.createServer( connect.static( __dirname ) )
.listen
(   config.port
,   function( ){
        log( 'started' )
        
        var io= require( 'socket.io' ).listen( server )
        
        var agents= {}
        var states= {}
        
        io.sockets.on( 'connection', function( socket ){
            log( 'connection' )
        
            socket.on( 'agent:ready', function( param ){
                log( 'agent: ' + param.id )
                agents[ param.id ]= socket
            } )
            
            socket.on( 'test:run', function( param ){
                log( 'broadcast run!' )
                states= {}
                socket.broadcast.emit( 'agent:run', { uri: config.uri })
                setTimeout( terminate, 30000 )
            } )
            
            socket.on( 'agent:done', function( param ){
                log( 'done: ' + param.id )
                states[ param.id ]= param.state
                console.log( states )
                for( var id in agents ){
                    if(!( id in states )) return
                }
                log( 'done: ' + JSON.stringify( states ) )
                socket.broadcast.emit( 'test:done', states )
            } )
            
            function terminate( ){
                log( 'timeout!' )
                for( var id in agents ){
                    states[ id ]= states[ id ]
                }
                socket.broadcast.emit( 'test:done', states )
            }
            
        } )
        
        void ( config.browsers || [] ).forEach( persistBrowser )
        
        function persistBrowser( path ){
            log( 'start: ' + path )
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
