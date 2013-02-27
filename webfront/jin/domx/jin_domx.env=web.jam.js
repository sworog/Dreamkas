this.$jin_domx=
$jin_wrapper( function( $jin_domx, domx ){
    
    domx.toDOMDocument=
    function( domx ){
        return domx.$
    }
    
    domx.toString= function( domx ){
        if( window.XMLSerializer ){
            var serializer= new XMLSerializer
            return serializer.serializeToString( domx.$ )
        } else {
            return String( domx.$.xml ).replace( /^\s+|\s+$/g, '' )
        }
    }
    
    domx.transform= function( domx, stylesheet ){
        if( window.XSLTProcessor ){
            var proc= new XSLTProcessor
            proc.importStylesheet( $jin_unwrap( stylesheet ) )
            var doc= proc.transformToDocument( domx.$ )
            return $jin_domx( doc )
        } else {
            var text= domx.$.transformNode( $jin_unwrap( stylesheet ) )
            return $jin_domx.parse( text )
        }
    }
    
    $jin_domx.parse= function( str ){
        if( window.DOMParser ){
            var parser= new DOMParser
            var doc= parser.parseFromString( str, 'text/xml' )
            return $jin_domx( doc )
        } else {
            var doc= new ActiveXObject( 'MSXML2.DOMDocument' )
            doc.async= false
            doc.loadXML( str )
            return $jin_domx( doc )
        }
    }

})
