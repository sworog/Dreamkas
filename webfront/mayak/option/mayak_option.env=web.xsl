<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template match=" mayak_option " >
        <option>
            <xsl:apply-templates />
        </option>
    </xsl:template>
    
</xsl:stylesheet>