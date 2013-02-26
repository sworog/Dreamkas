require( 'pms' ).$jin_persistent( function( ){ with( this ){
    $jin_build4web_all( 'mayak' )
    $jin_build4node_dev( 'mayak' ).load().$mayak_docServer()
}})