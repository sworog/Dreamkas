<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" mayak_product "
        >
        <div mayak_card="true">
            <div mayak_card_title="true">Создание товара</div>
            <form mayak_product_editor="maker">
                <xsl:apply-templates select=" . " mode="mayak_product_fields" />
                <button
                    mayak_button="success"
                    type="submit"
                    >
                    Создать товар
                </button>
            </form>
        </div>
    </xsl:template>
    
    <xsl:template
        match=" mayak_product[ @id ] "
        >
        <div mayak_card="true">
            <div mayak_card_title="true">Редактирование товара</div>
            <form mayak_product_editor="maker">
                <xsl:apply-templates select=" . " mode="mayak_product_fields" />
                <button
                    mayak_button="success"
                    type="submit"
                    >
                    Сохранить данные
                </button>
                <button
                    mayak_button="reset"
                    type="reset"
                    >
                    Отменить изменения
                </button>
            </form>
        </div>
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_fields" >
        <div mayak_block="true">
            <xsl:apply-templates select=" . " mode="mayak_product_article" />
            <xsl:apply-templates select=" . " mode="mayak_product_name" />
            <xsl:apply-templates select=" . " mode="mayak_product_manufacturer" />
            <xsl:apply-templates select=" . " mode="mayak_product_country" />
            <xsl:apply-templates select=" . " mode="mayak_product_price" />
            <xsl:apply-templates select=" . " mode="mayak_product_barcode" />
            <xsl:apply-templates select=" . " mode="mayak_product_dimension" />
            <xsl:apply-templates select=" . " mode="mayak_product_nds" />
            <xsl:apply-templates select=" . " mode="mayak_product_description" />
        </div>
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_article" >
        <input
            mayak_field="short"
            placeholder="Артикул"
            title="Артикул"
            type="text"
            name="article"
            value="{@article}"
        />
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_name" >
        <input
            mayak_field="normal"
            placeholder="Наименование"
            title="Наименование"
            required="required"
            type="text"
            name="name"
            value="{@name}"
        />
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_dimension" >
        <select
            mayak_field="short"
            required="required"
            title="Мерность"
            name="dimension"
            >
            <option value="">Мерность</option>
            <xsl:apply-templates select=" mayak_product_dimension / * ">
                <xsl:with-param name="selected" select="@dimension" />
            </xsl:apply-templates>
        </select>
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_nds" >
        <select
            mayak_field="short"
            required="required"
            title="НДС"
            name="nds"
            >
            <option value="">НДС</option>
            <xsl:apply-templates select=" mayak_product_nds / * ">
                <xsl:with-param name="selected" select="@nds" />
            </xsl:apply-templates>
        </select>
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_price" >
        <input
            mayak_field="short"
            type="number"
            step="any"
            required="required"
            placeholder="Закупочная цена"
            title="Закупочная цена"
            name="price"
            value="{ @price div 100 }"
        />
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_barcode" >
        <input
            mayak_field="short"
            type="number"
            required="required"
            placeholder="Штрих код"
            title="Штрих код"
            name="barcode"
            value="{ @barcode }"
        />
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_manufacturer" >
        <input
            mayak_field="normal"
            placeholder="Производитель"
            title="Производитель"
            required="required"
            type="text"
            name="manufacturer"
            value="{ @manufacturer }"
        />
    </xsl:template>

    <xsl:template match="mayak_product" mode="mayak_product_country" >
        <input
            mayak_field="normal"
            placeholder="Страна"
            title="Страна"
            required="required"
            type="text"
            name="country"
            value="{ @country }"
        />
    </xsl:template>
    
    <xsl:template match="mayak_product" mode="mayak_product_description" >
        <input
            mayak_field="long"
            placeholder="Дополнительная информация"
            title="Дополнительная информация"
            type="text"
            name="description"
            value="{ @description }"
        />
    </xsl:template>
    
</xsl:stylesheet>