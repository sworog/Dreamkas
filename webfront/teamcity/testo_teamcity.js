var config= require( './testo_config.js' )

require( 'child_process' ).spawn
(   'lh_server.cmd'
,   [ ]
,   { detached: true, cwd: '..' }
)

var server= require( 'child_process' ).spawn
(   'testo_server.cmd'
,   [ ]
,   { detached: true }
)

setTimeout( run, config.timeout )

function run(){

    var socket = require( 'socket.io-client' ).connect( '//' + config.host + ':' + config.port )
    
    socket.on( 'connect', function( ){
        console.log( "##teamcity[testSuiteStarted name='browser.unittest']" )
        
        console.log( "##teamcity[testStarted name='empty']" )
        console.log( "##teamcity[testFinished name='empty']" )
        console.log( "##teamcity[message text='test']" )
        
        socket.emit( 'test:run' )
        
        socket.on( 'test:done', function( states ){
            console.log( "##teamcity[message text='count: " + Object.keys( states ).length + "']" )
            
            for( var agent in states ){
                agent= agent.replace( /'/g, "|'" ).replace( /\n/g, "|n" ).replace( /\r/g, "|r" ).replace( /\|/g, "||" ).replace( /\]/g, "|]" )
                console.log( "##teamcity[testStarted name='" + agent + "']" )
                
                if( !states[ agent ] ) console.log( "##teamcity[testFailed  name='" + agent + "']" )
                
                console.log( "##teamcity[testFinished name='" + agent + "']" )
            }
            
            console.log( "##teamcity[testSuiteFinished name='browser.unittest']" )
            
            socket.disconnect()
            process.exit(0)
        } )
    })

}