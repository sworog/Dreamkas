<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_error ] "
        >
        <div lh_error="true">
            <xsl:value-of select=" exception / @message " />
        </div>
    </xsl:template>
    
</xsl:stylesheet>