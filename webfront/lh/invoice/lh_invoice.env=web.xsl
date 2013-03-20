<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
        match=" *[ @lh_application_view = 'lh_invoice' ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?invoice/list"
                >
                Накладные
            </a>
            <div lh_card="true">
                <xsl:apply-templates select=" html | error " mode="lh_error" />
                <xsl:apply-templates select=" invoice " mode="lh_invoice" />
            </div>
        </div>
    </xsl:template>

    <xsl:template
        match=" * "
        mode="lh_invoice" 
        >
        <div name="{ name() }">
            <div lh_card_header="true">
                <span lh_card_title="true">
                    Отложенная накладная №
                    <span name="sku">
                        <xsl:value-of select="sku" />
                    </span>
                    от
                    <span name="acceptanceDate">
                        <xsl:apply-templates select="acceptanceDate" mode="lh_date_view" />
                    </span>
                </span>
            </div>
            <div lh_stream="true">
                <span lh_stream_node="true">
                    <span name="supplier">
                        <xsl:value-of select="supplier" />
                    </span>
                    <span lh_stream_title="true">поставщик</span>
                </span>
                <span lh_stream_node="true">
                    <span name="accepter">
                        <xsl:value-of select="accepter" />
                    </span>
                    <span lh_stream_title="true">приёмщик</span>
                </span>
                <span lh_stream_node="true">
                    <span name="legalEntity">
                        <xsl:value-of select="legalEntity" />
                    </span>
                    <span lh_stream_title="true">получатель</span>
                </span>
            </div>
            <p lh_block="true">
                Входящая накладная № <xsl:value-of select="supplierInvoiceSku" />
                от <xsl:apply-templates select="supplierInvoiceDate" mode="lh_date_view" />
            </p>
        </div>
    </xsl:template>
    
</xsl:stylesheet>