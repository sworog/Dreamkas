this.$lh_resource= $jin_class( function( $lh_resource, resource ){
    
    $jin_registry.scheme( $lh_resource )
    
    resource.uri= null
    resource.xhr= null
    
    var initBase= resource.init
    resource.init= function( resource, uri ){
        initBase.apply( this, arguments )
        
        resource.uri= uri
        
        resource.xhr= new XMLHttpRequest
        resource.xhr.onerror= function( ){
            $lh_notify( 'Нет ответа от сервера. Попробуйте позже.' )
        }
    }
    
    resource.status= function( resource ){
        return Number( resource.xhr.status )
    }
    
    resource.message= function( resource ){
        return String( resource.xhr.statusText )
    }
    
    resource.content= function( resource ){
        return String( resource.xhr.responseText )
    }
    
    resource.xml= function( resource ){
        try {
            return $jin_domx.parse( resource.content() )
        } catch( error ){
            var content= $jin_domx.parse( '<error />' )
            content.attr( 'message', error )
            return content
        }
    }
    
    resource.request= function( resource, method, data, handler ){
        resource.xhr.open( method, resource.uri, true )
        
        resource.xhr.setRequestHeader( 'content-type', 'application/xml;charset=UTF-8' )
        
        resource.xhr.onload= function( ){
            resource.xhr.onload= null
            handler( resource )
        }
        
        resource.xhr.send( data && String( data ) )
        
        return resource
    }
    
    resource.head= function( resource, handler ){
        return resource.request( 'head', '', handler )
    }
    
    resource.get= function( resource, handler ){
        return resource.request( 'get', '', handler )
    }
    
    resource.post= function( resource, data, handler ){
        return resource.request( 'post', data, handler )
    }
    
    resource.put= function( resource, data, handler ){
        return resource.request( 'put', data, handler )
    }
    
    resource.drop= function( resource, data, handler ){
        return resource.request( 'delete', data, handler )
    }
    
    resource.patch= function( resource, data, handler ){
        return resource.request( 'patch', data, handler )
    }
    
    resource.isOk= function( resource ){
        return resource.status() == 200
    }
    
    resource.isCreated= function( resource ){
        return resource.status() == 201
    }
    
    resource.isSaved= function( resource ){
        return resource.status() == 204 // No Content, такой уж апи
    }
    
    resource.isWrongData= function( resource ){
        return resource.status() == 400
    }
    
    var destroyBase= resource.destroy
    resource.destroy= function( resource ){
        resource.xhr.abort()
        destroyBase.apply( this, arguments )
    }
    
} )
