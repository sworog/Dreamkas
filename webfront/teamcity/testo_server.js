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
                console.log( 'agent:', param.id )
                agents[ param.id ]= socket
            } )
            
            socket.on( 'test:run', function( param ){
                states= {}
                socket.broadcast.emit( 'agent:run', { uri: config.uri })
            } )
            
            socket.on( 'agent:done', function( param ){
                states[ param.id ]= param.state
                console.log( states )
                for( var id in agents ){
                    if(!( id in states )) return
                }
                
                socket.broadcast.emit( 'test:done', states )
            } )
          
        } )
        
        void ( config.browsers || [] ).forEach( persistBrowser )
        
        function persistBrowser( path ){
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
