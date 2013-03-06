<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template match="*" mode="lh_product_fields" >
        <div lh_block="true">
            <xsl:apply-templates select=" . " mode="lh_product_sku" />
            <xsl:apply-templates select=" . " mode="lh_product_name" />
        </div>
        <div lh_block="true">
            <xsl:apply-templates select=" . " mode="lh_product_vendor" />
            <xsl:apply-templates select=" . " mode="lh_product_vendorCountry" />
        </div>
        <div lh_block="true">
            <xsl:apply-templates select=" . " mode="lh_product_purchasePrice" />
            <xsl:apply-templates select=" . " mode="lh_product_barcode" />
            <xsl:apply-templates select=" . " mode="lh_product_unit" />
            <xsl:apply-templates select=" . " mode="lh_product_vat" />
        </div>
        <div lh_block="true">
            <xsl:apply-templates select=" . " mode="lh_product_info" />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_sku" >
        <label lh_field="short">
            Артикул
            <input
                required="required"
                type="text"
                name="sku"
                value="{@sku}"
            />
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_name" >
        <label lh_field="normal">
            Наименование
            <input
                required="required"
                type="text"
                name="name"
                value="{@name}"
            />
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_unit" >
        <label lh_field="short">
            Мерность
            <select
                required="required"
                title="Мерность"
                name="unit"
                >
                <option value="unit">
                    <xsl:if test=" not( @vat ) or @unit = 'unit' "><xsl:attribute name="selected"/></xsl:if>
                    Штуки
                </option>
                <option value="liter">
                    <xsl:if test=" @unit = 'liter' "><xsl:attribute name="selected"/></xsl:if>
                    Литры
                </option>
                <option value="kg">
                    <xsl:if test=" @unit = 'kg' "><xsl:attribute name="selected"/></xsl:if>
                    Килограммы
                </option>
            </select>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_vat" >
        <div lh_field="short">
            НДС
            <select
                required="required"
                name="vat"
                >
                <option value="0">
                    <xsl:if test=" @vat = '0' "><xsl:attribute name="selected"/></xsl:if>
                    0%
                </option>
                <option value="10">
                    <xsl:if test=" not( @vat ) or @vat = '10' "><xsl:attribute name="selected"/></xsl:if>
                    10%
                </option>
                <option value="18">
                    <xsl:if test=" @vat = '18' "><xsl:attribute name="selected"/></xsl:if>
                    18%
                </option>
            </select>
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_purchasePrice" >
        <div lh_field="short">
            Цена закупки
            <input
                step="any"
                required="required"
                name="purchasePrice"
                value="{ @purchasePrice }"
            />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_barcode" >
        <div lh_field="short">
            Штрих код
            <input
                required="required"
                name="barcode"
                value="{ @barcode }"
            />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_vendor" >
        <div lh_field="normal">
            Производитель
            <input
                required="required"
                type="text"
                name="vendor"
                value="{ @vendor }"
            />
        </div>
    </xsl:template>

    <xsl:template match="*" mode="lh_product_vendorCountry" >
        <div lh_field="normal">
            Страна
            <input
                required="required"
                type="text"
                name="vendorCountry"
                value="{ @vendorCountry }"
            />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_info" >
        <div lh_field="long">
            Дополнительная информация
            <input
                type="text"
                name="info"
                value="{ @info }"
            />
        </div>
    </xsl:template>
    
</xsl:stylesheet>