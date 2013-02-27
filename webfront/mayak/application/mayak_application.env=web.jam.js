this.$mayak_application= $jin_wrapper( function( $mayak_application, application ){
    
    application.templates= null
    
    application.render= function( application, xml ){
        application.$.innerHTML= $jin_domx.parse( xml ).transform( application.templates )
        return application
    }
    
    var init= application.init
    application.init= function( application, node ){
        init.apply( this, arguments )
        
        $jq.get
        (   'mayak/-mix/index.stage=release.xsl'
        ,   function( xsl, status, xhr ){
                application.templates= $jin_domx( xsl )
                switch( document.location.search ){
                    case '?product;create':
                        application.render( '<mayak_product id="1"/>' )
                        break
                    case '?product;list':
                        application.render( '<mayak_productList/>' )
                        break
                    default:
                        document.location= '?product;create'
                        break
                }
            }
        )
        
        $mayak_product_onSave.listen( document.body, function( event ){
            $jq.ajax
            (   '/api/1/product/create/'
            ,   {   type: 'post'
                ,   data: $jq( event.target() ).serialize()
                ,   success: function( data ){
                        document.location= '?productList'
                    }
                ,   error: function( data, type, message ){
                        message= message ? 'Ошибка при сохранении: ' + message : 'Неизвестная ошибка сохранения'
                        $mayak_notify( message )
                    }
                }
            )
            event.catched( true )
        })
        
    }
    
})

$jin_component( 'mayak_application', $mayak_application )
