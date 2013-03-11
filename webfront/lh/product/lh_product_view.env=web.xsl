<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
        match=" *[ @lh_product_view ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?product/list"
                >
                Список товаров
            </a>
            <div lh_card="true">
                <div lh_card_header="true">
                    <div lh_card_headerButtons="true">
                        <a
                            lh_button="modify"
                            type="submit"
                            href="?product={ id }/edit"
                            >
                            Изменить
                        </a>
                    </div>
                    <span lh_card_title="true">
                        <span
                            lh_card_titlePrefix="true"
                            title="Артикул"
                            >
                            <xsl:value-of select=" sku " />
                        </span>
                        <span title="Название">
                            <xsl:value-of select=" name " />
                        </span>
                    </span>
                </div>
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        Производитель:
                    </span>
                    <span lh_prop_value="true">
                        <xsl:value-of select=" vendor " />,
                        <xsl:value-of select=" vendorCountry " />
                    </span>
                </div>
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        Закупочная цена:
                    </span>
                    <span lh_prop_value="true">
                        <xsl:value-of select=" purchasePrice " /> руб.
                    </span>
                </div>
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        Штрих-код:
                    </span>
                    <span lh_prop_value="true">
                        <xsl:value-of select=" barcode " />
                    </span>
                </div>
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        Единица измерения:
                    </span>
                    <span lh_prop_value="true">
                        <xsl:apply-templates select=" units " mode="lh_product_view_units" />
                    </span>
                </div>
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        НДС:
                    </span>
                    <span lh_prop_value="true">
                        <xsl:value-of select=" vat " />%
                    </span>
                </div>
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        Дополнительная информация:
                    </span>
                    <span lh_prop_value="true">
                        <xsl:value-of select=" info " />
                    </span>
                </div>
            </div>
        </div>
    </xsl:template>
    
    <xsl:template match=" node() " mode="lh_product_view_units">
        <xsl:value-of select="." />
    </xsl:template>
    
    <xsl:template match=" node()[ . = 'unit' ] " mode="lh_product_view_units">
        штука
    </xsl:template>
    
    <xsl:template match=" node()[ . = 'kg' ] " mode="lh_product_view_units">
        килограмм
    </xsl:template>
    
    <xsl:template match=" node()[ . = 'liter' ] " mode="lh_product_view_units">
        литр
    </xsl:template>
    
</xsl:stylesheet>