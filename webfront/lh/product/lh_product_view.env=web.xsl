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
            <div lh_card="true" name="product">
                <div lh_card_header="true">
                    <xsl:apply-templates select=" . " mode="lh_product_view_buttons" />
                    <xsl:apply-templates select=" . " mode="lh_product_view_name" />
                    <xsl:apply-templates select=" . " mode="lh_product_view_sku" />
                </div>
                <xsl:apply-templates select=" . " mode="lh_product_view_purchasePrice" />
                <xsl:apply-templates select=" . " mode="lh_product_view_vat" />
                <xsl:apply-templates select=" . " mode="lh_product_view_barcode" />
                <xsl:apply-templates select=" . " mode="lh_product_view_units" />
                <xsl:apply-templates select=" . " mode="lh_product_view_vendor" />
                <xsl:apply-templates select=" . " mode="lh_product_view_vendorCountry" />
                <xsl:apply-templates select=" . " mode="lh_product_view_info" />
            </div>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_buttons">
        <div lh_card_headerButtons="true">
            <a
                lh_link="modify"
                href="?product={ id }/edit"
                >
                Редактировать
            </a>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_name">
        <div lh_card_title="true">
            <span title="Название" name="name">
                <xsl:value-of select=" name " />
            </span>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_sku">
        <div
            lh_sku="true"
            title="Артикул"
            name="sku"
            >
            <xsl:value-of select=" sku " />
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_purchasePrice">
        <div lh_prop="true">
            <span lh_prop_name="true">
                Закупочная цена:
            </span>
            <span lh_prop_value="true">
                <span name="purchasePrice">
                    <xsl:value-of select=" purchasePrice " />
                </span>
                руб.
            </span>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_vat">
        <div lh_prop="true">
            <span lh_prop_name="true">
                НДС:
            </span>
            <span lh_prop_value="true">
                <span name="vat">
                    <xsl:value-of select=" vat " />
                </span>
                %
            </span>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_vendor">
        <div lh_prop="true">
            <span lh_prop_name="true">
                Производитель:
            </span>
            <span lh_prop_value="true">
                <span name="vendor">
                    <xsl:value-of select=" vendor " />
                </span>
            </span>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_vendorCountry">
        <div lh_prop="true">
            <span lh_prop_name="true">
                Страна:
            </span>
            <span lh_prop_value="true">
                <span name="vendorCountry">
                    <xsl:value-of select=" vendorCountry " />
                </span>
            </span>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_barcode">
        <div lh_prop="true">
            <span lh_prop_name="true">
                Штрих-код:
            </span>
            <span lh_prop_value="true">
                <span name="barcode">
                    <xsl:value-of select=" barcode " />
                </span>
            </span>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_info">
        <div lh_block="true" name="info">
            <xsl:value-of select=" info " />
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_units">
        <div lh_prop="true">
            <span lh_prop_name="true">
                Единица измерения:
            </span>
            <span lh_prop_value="true">
                <span name="units" value="{units}">
                    <xsl:apply-templates select="." mode="lh_product_view_units_value" />
                </span>
            </span>
        </div>
    </xsl:template>
    
    <xsl:template match=" * " mode="lh_product_view_units_value">
        <xsl:value-of select="units" />
    </xsl:template>
    
    <xsl:template match=" *[ units = 'unit' ] " mode="lh_product_view_units_value">
        штука
    </xsl:template>
    <xsl:template match=" *[ units = 'kg' ] " mode="lh_product_view_units_value">
        килограмм
    </xsl:template>
    <xsl:template match=" *[ units = 'liter' ] " mode="lh_product_view_units_value">
        литр
    </xsl:template>
    
</xsl:stylesheet>