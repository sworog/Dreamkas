<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" mayak_product_view "
        >
        <xsl:apply-templates select=" product " mode="mayak_product_view" />
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="mayak_product_view"
        >
        <div mayak_card="true">
            <div mayak_card_title="true">
                <xsl:value-of select=" @name " />
            </div>
            <div mayak_block="true">
                Производитель:
                <xsl:value-of select=" @vendor " />,
                <xsl:value-of select=" @vendorCountry " />
            </div>
            <div mayak_block="true">
                Закупочная цена:
                <xsl:value-of select=" @purchasePrice " /> руб.
            </div>
            <div mayak_block="true">
                Артикул:
                <xsl:value-of select=" @sku " />
            </div>
            <div mayak_block="true">
                Штрих-код:
                <xsl:value-of select=" @barcode " />
            </div>
            <div mayak_block="true">
                Единица измерения:
                <xsl:value-of select=" @unit " />
            </div>
            <div mayak_block="true">
                НДС:
                <xsl:value-of select=" @sku " />
            </div>
            <div mayak_block="true">
                Дополнительная информация:
                <xsl:value-of select=" @info " />
            </div>
            <div mayak_block="true">
                <a
                    mayak_button="modify"
                    type="submit"
                    href="?product={ @id };edit"
                    >
                    Изменить
                </a>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>