<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" node() "
        mode="lh_money_view"
        >
        
        <xsl:value-of select=" translate( ., '.', ',' ) " />
        
    </xsl:template>
    
</xsl:stylesheet>