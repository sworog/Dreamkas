this.$lh_application= $jin_class( function( $lh_application, application ){
    
    $lh_widget( $lh_application )
    
    $lh_application.id= 'lh_application'
    
    application.templates= null
    
    application.render= function( application, data ){
        application.templates.render( data , application.$ )
        $jin_component.checkTree( application.$ )
        return application
    }
    
    application.view_product_edit= function( application, params ){
        $lh_resource( application.api() + 'products/' + params.product )
        .get( function( resource ){
            if( resource.isOk() ){
                product= resource.xml()
                product.attr( 'lh_product_edit', 'true' )
                application.render( product )
            } else {
                var error= resource.xml()
                error.attr( 'lh_product_error', 'true' )
                error.attr( 'lh_product_id', params.product )
                application.render( error )
            }
        } )
    }
    
    application.view_product_create= function( application, params ){
        application.render( $jin_domx.parse( '<product lh_product_create="true" />' ) )
    }
    
    application.view_product_list= function( application, params ){
        $lh_resource( application.api() + 'products' )
        .get( function( resource ){
            if( resource.isOk() ){
                products= resource.xml()
                products.attr( 'lh_product_list', 'true' )
                application.render( products )
            } else {
                var error= resource.xml()
                error.attr( 'lh_product_error', 'true' )
                application.render( error )
            }
        } )
    }
    
    application.view_product= function( application, params ){
        if( !params.product ){
            document.location= '?product/list'
            return
        }
        
        $lh_resource( application.api() + 'products/' + params.product )
        .get( function( resource ){
            if( resource.isOk() ){
                    product= resource.xml()
                    product.attr( 'lh_product_view', 'true' )
                    application.render( product )
            } else {
                var error= resource.xml()
                
                error.attr( 'lh_product_error', 'true' )
                error.attr( 'lh_product_id', params.product )
                
                application.render( error )
            }
        } )
    }
    
    application.view_invoice_create= function( application, params ){
        application.render( $jin_domx.parse( '<invoice lh_invoice_create="true" />' ) )
    }
    
    application.view_invoice= function( application, params ){
        if( !params.invoice ){
            document.location= '?invoice/list'
            return
        }
        
        $lh_resource( application.api() + 'invoices/' + params.invoice )
        .get( function( resource ){
            if( resource.isOk() ){
                    invoice= resource.xml()
                    invoice.attr( 'lh_invoice_view', 'true' )
                    application.render( invoice )
            } else {
                var error= resource.xml()
                
                error.attr( 'lh_invoice_error', 'true' )
                error.attr( 'lh_invoice_id', params.invoice )
                
                application.render( error )
            }
        } )
    }
    
    application.view_invoice_list= function( application, params ){
        //$lh_resource( application.api() + 'invoices' )
        $lh_resource( 'lh/invoice/lh_invoice_list.sample.xml' )
        .get( function( resource ){
            if( resource.isOk() ){
                invoices= resource.xml()
                invoices.attr( 'lh_invoice_list', 'true' )
                application.render( invoices )
            } else {
                var error= resource.xml()
                error.attr( 'lh_invoice_error', 'true' )
                application.render( error )
            }
        } )
    }
    
    application.view_= function( application, params ){
        document.location= '?product/list'
    }
    
    application.view_default= function( application, params ){
        var error= $jin_domx.parse( '<error/>' )
        error.attr( 'lh_error', 'true' )
        error.attr( 'message', 'No handler for ' + document.location.search )
        application.render( error )
    }
    
    application.api= function( application ){
        return application.$.getAttribute( 'lh_application_api' )
    }
    
    var init= application.init
    application.init= function( application, node ){
        init.apply( this, arguments )
        
        document.title= document.location.search
        
        $lh_resource( 'lh/-mix/index.stage=release.xsl' )
        .get( function initView( resource ){
            application.templates= resource.xml()
            
            var params= {}
            document.location.search
            .replace( /^\?/, '' )
            .split( /[\/&;]/ )
            .forEach( function( chunk ){
                var pair= chunk.split( '=' )
                params[ pair[ 0 ] ]= pair[ 1 ] || ''
            })
            
            var action= 'view_' + Object.keys( params ).join( '_' )
            
            var view= application[ action ] || application.view_default
            
            view.call( application, params )
        } )
        
        $lh_product_onSave.listen( document.body, function( event ){
            if( event.catched() ) return
            
            var editor= $lh_product_edit( event.target() )
            var data= editor.data()
            var id= data.select( 'id' )[ 0 ]
            if( id ) id= id.parent( null ).text()
            
            var url=  application.api() + 'products'
            if( id ) url+= '/' + id
            
            $lh_resource( url ).request( id ? 'put' : 'post', data, function( resource ){
                switch( true ){
                    case resource.isCreated():
                        id= resource.xml().select('id')[0].text()
                        document.location= '?product/list#product=' + id
                        break
                    case resource.isSaved():
                        document.location= '?product=' + id
                        break
                    case resource.isWrongData():
                        data= resource.xml()
                        editor.errors( data )
                        break;
                    default:
                        $lh_notify( 'Ошибка при сохранении данных товара. ' + resource.message() )
                }
            } )
            
            event.catched( true )
        })
        
        $lh_invoice_onSave.listen( document.body, function( event ){
            if( event.catched() ) return
            
            var editor= $lh_invoice_edit( event.target() )
            var data= editor.data()
            var id= data.select( 'id' )[ 0 ]
            if( id ) id= id.parent( null ).text()
            
            var url=  application.api() + 'invoices'
            if( id ) url+= '/' + id
            
            $lh_resource( url ).request( id ? 'put' : 'post', data, function( resource ){
                switch( true ){
                    case resource.isCreated():
                        id= resource.xml().select('id')[0].text()
                        document.location= '?invoice/list#invoice=' + id
                        break
                    case resource.isSaved():
                        document.location= '?invoice=' + id
                        break
                    case resource.isWrongData():
                        data= resource.xml()
                        editor.errors( data )
                        break;
                    default:
                        $lh_notify( 'Ошибка при сохранении данных накладной. ' + resource.message() )
                }
            } )
            
            event.catched( true )
        })
        
    }
    
})
