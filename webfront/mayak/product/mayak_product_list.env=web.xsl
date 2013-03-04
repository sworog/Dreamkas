<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template
        match=" *[ @mayak_product_list ] "
        >
        <div mayak_card="true">
            <div mayak_card_header="true">
                <div mayak_card_headerButtons="true">
                    <a
                        href="?product;create"
                        mayak_button="create"
                        >
                        Новый товар
                    </a>
                </div>
                <span mayak_card_title="true">
                    Список товаров
                </span>
            </div>
            
            <div mayak_table="true">
                
                <div mayak_table_row="true">
                    <span
                        mayak_table_cell="id"
                        mayak_table_header="true"
                        >
                        Артикул
                    </span>
                    <span
                        mayak_table_cell="common"
                        mayak_table_header="true"
                        >
                        Название
                    </span>
                    <span
                        mayak_table_cell="common"
                        mayak_table_header="true"
                        >
                        Производитель
                    </span>
                    <span
                        mayak_table_cell="common"
                        mayak_table_header="true"
                        >
                        Страна
                    </span>
                    <span
                        mayak_table_cell="money"
                        mayak_table_header="true"
                        >
                        Цена
                    </span>
                </div>
                
                <xsl:apply-templates select=" product " mode="mayak_product_list" />
            </div>
            
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="mayak_product_list"
        >
        <a
            mayak_table_row="true"
            id="product={ @id }"
            href="?product={ @id }"
            >
            <span mayak_table_cell="id">
                <xsl:value-of select=" @sku " />
            </span>
            <span mayak_table_cell="common">
                <xsl:value-of select=" @name " />
            </span>
            <span mayak_table_cell="common">
                <xsl:value-of select=" @vendor " />
            </span>
            <span mayak_table_cell="common">
                <xsl:value-of select=" @vendorCountry " />
            </span>
            <span mayak_table_cell="money">
                <xsl:value-of select=" @purchasePrice " /> р.
            </span>
        </a>
    </xsl:template>

</xsl:stylesheet>