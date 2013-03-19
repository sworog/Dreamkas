<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
        match=" *[ @lh_invoice_view ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?invoice/list"
                >
                Накладные
            </a>
            <div lh_card="true" name="{ name() }">
                <div lh_card_header="true">
                    <span lh_card_title="true">
                        Отложенная накладная №<xsl:value-of select="sku" />
                        от <xsl:apply-templates select="acceptanceDate" mode="lh_date_view" />
                    </span>
                </div>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>