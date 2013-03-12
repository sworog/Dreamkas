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
        var node= domx.toDOMNode()
        if( arguments.length > 2 ){
            if( value == null ) node.removeAttribute( name )
            else node.setAttribute( name, value )
        } else {
            return node.getAttribute( name )
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
