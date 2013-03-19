<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_product_error ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?product/list"
                >
                Товары
            </a>
            <div lh_card="true">
                <div lh_error="true">
                    <xsl:apply-templates select="." mode="lh_product_error_message" />
                </div>
            </div>
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="lh_product_error_message"
        >
        <xsl:apply-templates select=" . " mode="lh_error_message" />
    </xsl:template>
    
    <xsl:template
        match=" *[ @code = 404 ] "
        mode="lh_product_error_message"
        >
        Товар с идентификатором <xsl:value-of select=" @lh_product_id " /> не найден.
    </xsl:template>
    
</xsl:stylesheet>