<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_error ] "
        >
        <div lh_card_stack="true">
            <div lh_card="true">
                <xsl:apply-templates select="." mode="lh_error_view" />
            </div>
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="lh_error"
        >
        <div lh_error="true">
            <xsl:value-of select=" exception / @message | @message | head / title " />
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="lh_error_message"
        >
        <xsl:value-of select=" exception / @message | @message | head / title " />
    </xsl:template>
    
</xsl:stylesheet>