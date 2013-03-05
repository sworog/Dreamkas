<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @mayak_product_creator ] "
        >
        <div mayak_card_stack="true">
            <a
                mayak_card_back="true"
                href="?product;list"
                >
                Список товаров
            </a>
            <div mayak_card="true">
                <div mayak_card_title="true">Новый товар</div>
                <form mayak_product_editor="maker">
                    <xsl:apply-templates select=" . " mode="mayak_product_fields" />
                    <div mayak_block="true">
                        <button
                            mayak_button="success"
                            type="submit"
                            disabled="disabled"
                            >
                            Создать товар
                        </button>
                        <a
                            mayak_button="reset"
                            href="?product;list"
                            >
                            Отменить
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>