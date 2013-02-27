this.$mayak_component= function( id, widget ){
    var nodes= document.querySelectorAll( '*[' + id + ']' )
    for( var i= 0; i < nodes.length; ++i ){
        widget( nodes[ i ] )
    }
}
