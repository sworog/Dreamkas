<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template match="*" mode="lh_product_fields" >
        <xsl:apply-templates select=" . " mode="lh_product_id" />
        <xsl:apply-templates select=" . " mode="lh_product_sku" />
        <xsl:apply-templates select=" . " mode="lh_product_name" />
        <xsl:apply-templates select=" . " mode="lh_product_units" />
        <xsl:apply-templates select=" . " mode="lh_product_purchasePrice" />
        <xsl:apply-templates select=" . " mode="lh_product_vat" />
        <xsl:apply-templates select=" . " mode="lh_product_barcode" />
        <xsl:apply-templates select=" . " mode="lh_product_vendorCountry" />
        <xsl:apply-templates select=" . " mode="lh_product_vendor" />
        <xsl:apply-templates select=" . " mode="lh_product_info" />
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_id" />
    <xsl:template match="*[ id ]" mode="lh_product_id" >
        <input
            type="hidden"
            name="id"
            value="{ id }"
        />
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_sku" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Артикул
            </span>
            <span lh_prop_value="true" lh_field="normal">
                <input
                    lh_field_native="true"
                    name="sku"
                    value="{sku}"
                />
            </span>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_name" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Наименование
            </span>
            <span lh_prop_value="true" lh_field="normal">
                <input
                    lh_field_native="true"
                    name="name"
                    value="{name}"
                />
            </span>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_units" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Мерность
            </span>
            <span lh_prop_value="true" lh_field="short">
                <select
                    lh_field_native="true"
                    name="units"
                    >
                    <xsl:if test=" not( units ) ">
                        <option></option>
                    </xsl:if>
                    <option value="unit">
                        <xsl:if test=" units = 'unit' "><xsl:attribute name="selected"/></xsl:if>
                        Штуки
                    </option>
                    <option value="liter">
                        <xsl:if test=" units = 'liter' "><xsl:attribute name="selected"/></xsl:if>
                        Литры
                    </option>
                    <option value="kg">
                        <xsl:if test=" units = 'kg' "><xsl:attribute name="selected"/></xsl:if>
                        Килограммы
                    </option>
                </select>
            </span>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_vat" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                НДС
            </span>
            <span lh_prop_value="true" lh_field="short">
                <select
                    lh_field_native="true"
                    name="vat"
                    >
                    <xsl:if test=" not( vat ) ">
                        <option></option>
                    </xsl:if>
                    <option value="0">
                        <xsl:if test=" vat = '0' "><xsl:attribute name="selected"/></xsl:if>
                        0%
                    </option>
                    <option value="10">
                        <xsl:if test=" vat = '10' "><xsl:attribute name="selected"/></xsl:if>
                        10%
                    </option>
                    <option value="18">
                        <xsl:if test=" vat = '18' "><xsl:attribute name="selected"/></xsl:if>
                        18%
                    </option>
                </select>
            </span>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_purchasePrice" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Цена закупки
            </span>
            <span lh_prop_value="true" lh_field="short">
                <input
                    lh_field_native="true"
                    placeholder="0,00"
                    name="purchasePrice"
                    value="{ purchasePrice }"
                />
            </span>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_barcode" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Штрих код
            </span>
            <span lh_prop_value="true" lh_field="short">
                <input
                    lh_field_native="true"
                    name="barcode"
                    value="{ barcode }"
                />
            </span>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_vendor" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Производитель
            </span>
            <span lh_prop_value="true" lh_field="normal">
                <input
                    lh_field_native="true"
                    name="vendor"
                    value="{ vendor }"
                />
            </span>
        </label>
    </xsl:template>

    <xsl:template match="*" mode="lh_product_vendorCountry" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Страна
            </span>
            <span lh_prop_value="true" lh_field="normal">
                <input
                    lh_field_native="true"
                    name="vendorCountry"
                    value="{ vendorCountry }"
                />
            </span>
        </label>
    </xsl:template>
    
    <xsl:template match="*" mode="lh_product_info" >
        <label lh_prop="true">
            <span lh_prop_name="true">
                Дополнительная информация
            </span>
            <span lh_prop_value="true" lh_field="long">
                <textarea
                    lh_field_native="true"
                    name="info"
                    >
                    <xsl:value-of select=" info " />
                </textarea>
            </span>
        </label>
    </xsl:template>
    
</xsl:stylesheet>