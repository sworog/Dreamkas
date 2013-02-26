this.$mayak_server= function( ){
    
    $node.express()
    .use( $jin_middle4static() )
    .use( $node.express.directory( $jin_file( '.' ) ) )
    .listen( 8008 )
    
    console.log( 'Started [mayak] @ localhost:8008' )
}
