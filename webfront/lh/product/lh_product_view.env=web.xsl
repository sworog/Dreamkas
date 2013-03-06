<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
        match=" *[ @lh_product_view ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?product;list"
                >
                Список товаров
            </a>
            <div lh_card="true">
                <div lh_card_header="true">
                    <div lh_card_headerButtons="true">
                        <a
                            lh_button="modify"
                            type="submit"
                            href="?product={ @id };edit"
                            >
                            Изменить
                        </a>
                    </div>
                    <span lh_card_title="true">
                        <span
                            lh_card_titlePrefix="true"
                            title="Артикул"
                            >
                            <xsl:value-of select=" @sku " />
                        </span>
                        <span title="Название">
                            <xsl:value-of select=" @name " />
                        </span>
                    </span>
                </div>
                <div lh_block="true">
                    Производитель:
                    <xsl:value-of select=" @vendor " />,
                    <xsl:value-of select=" @vendorCountry " />
                </div>
                <div lh_block="true">
                    Закупочная цена:
                    <xsl:value-of select=" @purchasePrice " /> руб.
                </div>
                <div lh_block="true">
                    Штрих-код:
                    <xsl:value-of select=" @barcode " />
                </div>
                <div lh_block="true">
                    Единица измерения:
                    <xsl:value-of select=" @unit " />
                </div>
                <div lh_block="true">
                    НДС:
                    <xsl:value-of select=" @vat " />%
                </div>
                <div lh_block="true">
                    Дополнительная информация:
                    <xsl:value-of select=" @info " />
                </div>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>