this.$jin_domx=
$jin_class( function( $jin_domx, domx ){
    
    $jin_wrapper.scheme( $jin_domx )
    
    domx.toDOMDoc=
    function( domx ){
        return domx.$.ownerDocument || domx.$
    }
    
    domx.toDOMNode=
    function( domx ){
        return domx.$
    }
    
    domx.toString= function( domx ){
        if( $jin_support.xmlModel() === 'w3c' ){
            var serializer= new XMLSerializer
            return serializer.serializeToString( domx.$ )
        } else {
            return String( domx.$.xml ).replace( /^\s+|\s+$/g, '' )
        }
    }
    
    domx.transform= function( domx, stylesheet ){
        if( $jin_support.xmlModel() === 'w3c' ){
            var proc= new XSLTProcessor
            proc.importStylesheet( $jin_unwrap( stylesheet ) )
            var doc= proc.transformToDocument( domx.$ )
            return $jin_domx( doc )
        } else { // works incorrectly =(
            var result= domx.$.transformNode( $jin_unwrap( stylesheet ) )
            return $jin_domx.parse( result )
        }
    }
    
    domx.render= function( domx, from, to ){
        from= $jin_unwrap( from )
        to= $jin_unwrap( to )
        
        if( $jin_support.xmlModel() === 'w3c' ){
            var proc= new XSLTProcessor
            proc.importStylesheet( domx.toDOMDoc() )
            var res= proc.transformToFragment( from, to.ownerDocument )
            to.innerHTML= ''
            to.appendChild( res )
        } else {
            to.innerHTML= from.transformNode( domx.toDOMDoc() )
        }
        
        return domx
    }
    
    domx.attr= function( domx, name, value ){
        if( arguments.length > 2 ){
            if( value == null ) domx.$.removeAttribute( name )
            else domx.$.setAttribute( name, value )
            return domx
        } else {
            return domx.$.getAttribute( name )
        }
    }
    
    domx.text= function( domx, value ){
        if( arguments.length > 1 ){
            domx.clear()
            if( value != '' ) domx.Text( value ).parent( domx )
            return domx
        } else {
            return domx.$.textContent
        }
    }
    
    domx.clear= function( domx ){
        var child
        while( child= domx.$.firstChild ){
            domx.$.removeChild( child )
        }
        return domx
    }
    
    domx.parent= function( domx, parent ){
        if( arguments.length > 1 ){
            if( parent == null ){
                parent= node.$.parentNode
                if( parent ) parent.removeChild( domx.$ )
            } else {
                $jin_unwrap( parent ).appendChild( domx.$ )
            }
            return domx
        } else {
            parent= node.$.parentNode
            return parent ? $jin_domx( parent ) : parent
        }
    }

    domx.Text= function( domx, value ){
        return $jin_domx( domx.toDOMDoc().createTextNode( value ) )
    }
    
    domx.Fragment= function( domx ){
        return $jin_domx( domx.toDOMDoc().createDocumentFragment() )
    }
    
    domx.Element= function( domx, name, ns ){
        var doc= domx.toDOMDoc()
        if( arguments.length > 2 ){
            return $jin_domx( doc.createElementNS( ns, name ) )
        } else {
            return $jin_domx( doc.createElement( name ) )
        }
    }
    
    $jin_domx.parse= function( str ){
        if( $jin_support.xmlModel() === 'w3c' ){
            var parser= new DOMParser
            var doc= parser.parseFromString( str, 'text/xml' )
            return $jin_domx( doc.documentElement )
        } else {
            var doc= new ActiveXObject( 'MSXML2.DOMDocument' )
            doc.async= false
            doc.loadXML( str )
            return $jin_domx( doc.documentElement )
        }
    }

})
