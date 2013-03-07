this.$lh_application= $jin_wrapper( function( $lh_application, application ){
    
    application.templates= null
    
    application.render= function( application, data ){
        application.templates.render( data , application.$ )
        $jin_component.checkTree( application.$ )
        return application
    }
    
    application.view_product_edit= function( application, params ){
        $jq.get
        (   application.api() + 'products/' + params.product
        ,   function( product, status, xhr ){
                product= $jin_domx.parse( xhr.responseText )
                product.$.documentElement.setAttribute( 'lh_product_editor', 'true' )
                application.render( product )
            }
        )
    }
    
    application.view_product_create= function( application, params ){
        application.render( $jin_domx.parse( '<product lh_product_creator="true" />' ) )
    }
    
    application.view_product_list= function( application, params ){
        $jq.ajax
        (   application.api() + 'products'
        ,   {   success: function( products, status, xhr ){
                    products= $jin_domx.parse( xhr.responseText )
                    products.$.documentElement.setAttribute( 'lh_product_list', 'true' )
                    application.render( products )
                }
            ,   error: function( data, type, message ){
                    message= message
                    ? 'Ошибка получения списка товаров: ' + message
                    : 'Неизвестная ошибка получения списка товаров'
                    $lh_notify( message )
                }
            }
        )
    }
    
    application.view_product= function( application, params ){
        $jq.ajax
        (   application.api() + 'products/' + params.product
        ,   {   success: function( product, status, xhr ){
                    product= $jin_domx.parse( xhr.responseText )
                    product.$.documentElement.setAttribute( 'lh_product_view', 'true' )
                    application.render( product )
                }
            ,   error: function( data, type, message ){
                    try {
                        var error= $jin_domx.parse( data.responseText )
                    } catch( error ){
                        var error= $jin_domx.parse( '<error />' )
                    }
                    
                    error.$.documentElement.setAttribute( 'lh_product_error' )
                    error.$.documentElement.setAttribute( 'lh_product_id', params.product )
                    application.render( error )
                }
            }
        )
    }
        
    application.view_= function( application, params ){
        document.location= '?product;list'
    }
    
    application.view_default= function( application, params ){
        $lh_notify( 'No handler for ' + JSON.stringify( params )  )
    }
    
    application.api= function( application ){
        return application.$.getAttribute( 'lh_application_api' )
    }
    
    var init= application.init
    application.init= function( application, node ){
        init.apply( this, arguments )
        
        $jq.get
        (   'lh/-mix/index.stage=release.xsl'
        ,   initView
        )
        
        function initView( xsl, status, xhr ){
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
        
        $lh_product_onSave.listen( document.body, function( event ){
            if( event.catched() ) return
            
            $jq.ajax
            (   application.api() + 'products'
            ,   {   type: 'post'
                ,   contentType: 'application/xml; charset=utf-8'
                ,   data: $lh_product_editor( event.target() ).data() + ''
                ,   success: function( data ){
                        document.location= '?product;list#product=3'
                    }
                ,   error: function( data, type, message ){
                        message= message
                        ? 'Ошибка при сохранении данных товара: ' + message
                        : 'Неизвестная ошибка сохранения данных товара'
                        $lh_notify( message )
                    }
                }
            )
            event.catched( true )
        })
        
    }
    
})

$jin_component( 'lh_application', $lh_application )