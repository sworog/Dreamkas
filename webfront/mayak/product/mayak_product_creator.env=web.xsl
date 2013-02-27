<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" mayak_product_creator "
        >
        <div mayak_card="true">
            <div mayak_card_title="true">Создание товара</div>
            <form mayak_product_editor="maker">
                <xsl:apply-templates select=" . " mode="mayak_product_fields" />
                <div mayak_block="true">
                    <button
                        mayak_button="success"
                        type="submit"
                        >
                        Создать товар
                    </button>
                </div>
            </form>
        </div>
    </xsl:template>
    
</xsl:stylesheet>