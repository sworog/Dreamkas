this.$mayak_application= $jin_wrapper( function( $mayak_application, application ){
    
    application.templates= null
    
    application.render= function( application, data ){
        application.$.innerHTML= data.transform( application.templates )
        return application
    }
    
    application.view_product_edit= function( application, params ){
        $jq.get
        (   application.api() + 'product/' + params.product
        ,   function( product, status, xhr ){
                product= $jin_domx.parse( xhr.responseText )
                product.$.documentElement.setAttribute( 'mayak_product_editor', 'true' )
                application.render( product )
            }
        )
    }
        
    application.view_product_create= function( application, params ){
        application.render( $jin_domx.parse( '<product mayak_product_creator="true" />' ) )
    }
        
    application.view_product= function( application, params ){
        $jq.ajax
        (   application.api() + 'product/' + params.product
        ,   {   success: function( product, status, xhr ){
                    product= $jin_domx.parse( xhr.responseText )
                    product.$.documentElement.setAttribute( 'mayak_product_view', 'true' )
                    application.render( product )
                }
            ,   error: function( data, type, message ){
                    message= message
                    ? 'Ошибка получения данных товара: ' + message
                    : 'Неизвестная ошибка получения данных товара'
                    $mayak_notify( message )
                }
            }
        )
    }
        
    application.view_product_list= function( application, params ){
        application.render( '<mayak_productList/>' )
    }
    
    application.view_= function( application, params ){
        document.location= '?product;create'
    }
    
    application.view_default= function( application, params ){
        $mayak_notify( 'No handler for ' + JSON.stringify( params )  )
    }
    
    application.api= function( application ){
        return application.$.getAttribute( 'mayak_application_api' )
    }
    
    var init= application.init
    application.init= function( application, node ){
        init.apply( this, arguments )
        
        $jq.get
        (   'mayak/-mix/index.stage=release.xsl'
        ,   function( xsl, status, xhr ){
                application.templates= $jin_domx.parse( xhr.responseText )
                
                var params= {}
                document.location.search
                .replace( /^\?/, '' )
                .split( ';' )
                .forEach( function( chunk ){
                    var pair= chunk.split( '=' )
                    params[ pair[ 0 ] ]= pair[ 1 ] || ''
                })
                
                var action= 'view_' + Object.keys( params ).join( '_' )
                
                var view= application[ action ] || application.view_default
                
                view.call( application, params )
            }
        )
        
        $mayak_product_onSave.listen( document.body, function( event ){
            $jq.ajax
            (   application.api() + 'product'
            ,   {   type: 'post'
                ,   data: $jq( event.target() ).serialize()
                ,   success: function( data ){
                        $mayak_notify( 'Продукт успешно создан' )
                        document.location= '?product;create'
                    }
                ,   error: function( data, type, message ){
                        message= message
                        ? 'Ошибка при сохранении данных товара: ' + message
                        : 'Неизвестная ошибка сохранения данных товара'
                        $mayak_notify( message )
                    }
                }
            )
            event.catched( true )
        })
        
    }
    
})

$jin_component( 'mayak_application', $mayak_application )
