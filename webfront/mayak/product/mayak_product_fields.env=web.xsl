<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template match="*" mode="mayak_product_fields" >
        <div mayak_block="true">
            <xsl:apply-templates select=" . " mode="mayak_product_sku" />
            <xsl:apply-templates select=" . " mode="mayak_product_name" />
        </div>
        <div mayak_block="true">
            <xsl:apply-templates select=" . " mode="mayak_product_vendor" />
            <xsl:apply-templates select=" . " mode="mayak_product_vendorCountry" />
        </div>
        <div mayak_block="true">
            <xsl:apply-templates select=" . " mode="mayak_product_purchasePrice" />
            <xsl:apply-templates select=" . " mode="mayak_product_barcode" />
            <xsl:apply-templates select=" . " mode="mayak_product_unit" />
            <xsl:apply-templates select=" . " mode="mayak_product_vat" />
        </div>
        <div mayak_block="true">
            <xsl:apply-templates select=" . " mode="mayak_product_info" />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="mayak_product_sku" >
        <label mayak_field="short">
            Артикул
            <input
                required="required"
                type="text"
                name="sku"
                value="{@sku}"
            />
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="mayak_product_name" >
        <label mayak_field="normal">
            Наименование
            <input
                required="required"
                type="text"
                name="name"
                value="{@name}"
            />
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="mayak_product_unit" >
        <label mayak_field="short">
            Мерность
            <select
                required="required"
                title="Мерность"
                name="unit"
                >
                <option value=""></option>
                <option value="unit">
                    <xsl:if test=" @unit = 'unit' "><xsl:attribute name="selected"/></xsl:if>
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
    
    <xsl:template match="*" mode="mayak_product_vat" >
        <div mayak_field="short">
            НДС
            <select
                required="required"
                name="vat"
                >
                <option value=""></option>
                <option value="1">
                    <xsl:if test=" @vat = '0' "><xsl:attribute name="selected"/></xsl:if>
                    0%
                </option>
                <option value="5">
                    <xsl:if test=" @vat = '10' "><xsl:attribute name="selected"/></xsl:if>
                    10%
                </option>
                <option value="10">
                    <xsl:if test=" @vat = '18' "><xsl:attribute name="selected"/></xsl:if>
                    18%
                </option>
            </select>
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="mayak_product_purchasePrice" >
        <div mayak_field="short">
            Цена закупки
            <input
                step="any"
                required="required"
                name="purchasePrice"
                value="{ @purchasePrice }"
            />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="mayak_product_barcode" >
        <div mayak_field="short">
            Штрих код
            <input
                required="required"
                name="barcode"
                value="{ @barcode }"
            />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="mayak_product_vendor" >
        <div mayak_field="normal">
            Производитель
            <input
                required="required"
                type="text"
                name="vendor"
                value="{ @vendor }"
            />
        </div>
    </xsl:template>

    <xsl:template match="*" mode="mayak_product_vendorCountry" >
        <div mayak_field="normal">
            Страна
            <input
                required="required"
                type="text"
                name="vendorCountry"
                value="{ @vendorCountry }"
            />
        </div>
    </xsl:template>
    
    <xsl:template match="*" mode="mayak_product_info" >
        <div mayak_field="long">
            Дополнительная информация
            <input
                type="text"
                name="info"
                value="{ @info }"
            />
        </div>
    </xsl:template>
    
</xsl:stylesheet>