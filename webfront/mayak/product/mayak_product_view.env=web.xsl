<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
        match=" *[ @mayak_product_view ] "
        >
        <a
            mayak_card_back="true"
            href="?product;list"
            >
            Список товаров
        </a>
        <div mayak_card="true">
            <div mayak_card_header="true">
                <div mayak_card_headerButtons="true">
                    <a
                        mayak_button="modify"
                        type="submit"
                        href="?product={ @id };edit"
                        >
                        Изменить
                    </a>
                </div>
                <span mayak_card_title="true">
                    <span
                        mayak_card_titlePrefix="true"
                        title="Артикул"
                        >
                        <xsl:value-of select=" @sku " />
                    </span>
                    <span title="Название">
                        <xsl:value-of select=" @name " />
                    </span>
                </span>
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
                Штрих-код:
                <xsl:value-of select=" @barcode " />
            </div>
            <div mayak_block="true">
                Единица измерения:
                <xsl:value-of select=" @unit " />
            </div>
            <div mayak_block="true">
                НДС:
                <xsl:value-of select=" @vat " />%
            </div>
            <div mayak_block="true">
                Дополнительная информация:
                <xsl:value-of select=" @info " />
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>