<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template match=" * " mode="mayak_option">
        <xsl:param name="selected" select="''" />
        <option>
            <xsl:copy-of select=" @value " />
            <xsl:if test=" $selected = @value ">
                <xsl:attribute name="selected">selected</xsl:attribute>
            </xsl:if>
            <xsl:apply-templates />
        </option>
    </xsl:template>
    
</xsl:stylesheet>