require( 'pms' ).$jin_persistent( function( ){ with( this ){
    with( $jin_build4node_dev( 'pms' ).load() ){
        $jin_build4web_all( 'doc' )
        $jin_build4web_all( 'lh' )
        $jin_build4node_dev( 'lh' ).load().$lh_server()
    }
}})