<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_invoice_error ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?invoice/list"
                >
                Накладные
            </a>
            <div lh_card="true">
                <div lh_error="true">
                    <xsl:apply-templates select="." mode="lh_invoice_errorMessage" />
                </div>
            </div>
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="lh_invoice_errorMessage"
        >
        <xsl:value-of select=" exception / @message | @message " />
    </xsl:template>
    
    <xsl:template
        match=" *[ @code = 404 ] "
        mode="lh_invoice_errorMessage"
        >
        Накладная с идентификатором <xsl:value-of select=" @lh_invoice_id " /> не найдена.
    </xsl:template>
    
</xsl:stylesheet>