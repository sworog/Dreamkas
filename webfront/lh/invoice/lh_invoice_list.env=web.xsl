<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
        match=" *[ @lh_invoice_list ] "
        >
        <div lh_card_stack="true">
            <div lh_card="true">
                <div lh_card_header="true">
                    <span lh_card_title="true">
                        Список товаров
                    </span>
                    <a
                        href="?invoice/create"
                        lh_button="create"
                        >
                        Новая накладная
                    </a>
                </div>
                
                <xsl:apply-templates select=" . " mode="lh_invoice_list" />
                
            </div>
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="lh_invoice_list"
        >
    </xsl:template>
    
    <xsl:template
        match=" *[ invoice ] "
        mode="lh_invoice_list"
        >
        <div lh_table="true" name="invoices">
            
            <div lh_table_row="true">
                <span
                    lh_table_cell="id"
                    lh_table_header="true"
                    >
                    Артикул
                </span>
                <span
                    lh_table_cell="common"
                    lh_table_header="true"
                    >
                    Поставщик
                </span>
                <span
                    lh_table_cell="money"
                    lh_table_header="true"
                    >
                    Сумма
                </span>
                <span
                    lh_table_cell="common"
                    lh_table_header="true"
                    >
                    Принял
                </span>
            </div>
            
            <xsl:apply-templates select=" invoice " mode="lh_invoice_list_item" />
        </div>
    </xsl:template>

    <xsl:template
        match=" * "
        mode="lh_invoice_list_item"
        >
        <a
            lh_table_row="true"
            name="invoice"
            id="invoice={ id }"
            href="?invoice={ id }"
            >
            <span lh_table_cell="id" name="sku">
                <xsl:value-of select=" sku " />
            </span>
            <span lh_table_cell="common" name="supplier">
                <xsl:value-of select=" supplier " />
            </span>
            <span lh_table_cell="money">
                <span name="sumTotal">
                    <xsl:apply-templates select=" sumTotal " mode="lh_money_view" />
                </span>
                р.
            </span>
            <span lh_table_cell="common" name="accepter">
                <xsl:value-of select=" accepter " />
            </span>
        </a>
    </xsl:template>

</xsl:stylesheet>