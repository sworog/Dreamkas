<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" mayak_product "
        >
        <xsl:apply-templates
            select=" . "
            mode="mayak_product_maker"
        />
    </xsl:template>
    
    <xsl:template
        match=" mayak_product "
        mode="mayak_product_maker"
        >
        <mayak_card>
            <mayak_card_title>Создание товара</mayak_card_title>
            <form>
                <mayak_block>
                    <xsl:apply-templates />
                </mayak_block>
                <button
                    mayak_button="success"
                    type="submit"
                    >
                    Создать товар
                </button>
            </form>
        </mayak_card>
    </xsl:template>
    
    <xsl:template match=" mayak_product_article " >
        <input
            mayak_field="short"
            placeholder="Артикул"
            type="text"
        />
    </xsl:template>
    
    <xsl:template match=" mayak_product_name " >
        <input
            mayak_field="normal"
            placeholder="Наименование"
            required="required"
            type="text"
        />
    </xsl:template>
    
    <xsl:template match=" mayak_product_dim " >
        <select
            mayak_field="short"
            required="required"
            title="Мерность"
            >
            <option value="">Мерность</option>
            <xsl:apply-templates />
        </select>
    </xsl:template>
    
    <xsl:template match=" mayak_product_nds " >
        <select
            mayak_field="short"
            required="required"
            title="НДС"
            >
            <option value="">НДС</option>
            <xsl:apply-templates />
        </select>
    </xsl:template>
    
    <xsl:template match=" mayak_product_cost " >
        <input
            mayak_field="short"
            type="number"
            required="required"
            placeholder="Закупочная цена"
            title="Закупочная цена"
        />
    </xsl:template>
    
    <xsl:template match=" mayak_product_barcode " >
        <input
            mayak_field="short"
            type="number"
            required="required"
            placeholder="Штрих код"
            title="Штрих код"
        />
    </xsl:template>
    
    <xsl:template match=" mayak_product_manufacturer " >
        <input
            mayak_field="normal"
            placeholder="Производитель"
            required="required"
            type="text"
        />
    </xsl:template>

    <xsl:template match=" mayak_product_country " >
        <input
            mayak_field="normal"
            placeholder="Страна"
            required="required"
            type="text"
        />
    </xsl:template>
    
    <xsl:template match=" mayak_product_descr " >
        <input
            mayak_field="long"
            placeholder="Дополнительная информация"
            title="Дополнительная информация"
            type="text"
        />
    </xsl:template>
    
</xsl:stylesheet>