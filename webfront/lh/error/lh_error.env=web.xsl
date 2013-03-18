<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_error ] "
        >
        <div lh_card_stack="true">
            <div lh_card="true">
                <div lh_error="true">
                    <xsl:apply-templates select="." mode="lh_error_message" />
                </div>
            </div>
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="lh_error_message"
        >
        <xsl:value-of select=" exception / @message | @message " />
    </xsl:template>
    
</xsl:stylesheet>