var config= require( './testo_config.js' )
var socket = require( 'socket.io-client' ).connect( '//' + config.host + ':' + config.port )

socket.emit( 'test:run' )

socket.on( 'test:done', function( states ){
    
    for( var agent in states ){
        if( states[ agent ] )
            continue
        
        console.error( 'FAULT: ' + agent )
    }
    
    socket.disconnect()
} )
