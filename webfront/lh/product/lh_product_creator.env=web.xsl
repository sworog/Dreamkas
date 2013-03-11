<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_product_creator ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?product/list"
                >
                Список товаров
            </a>
            <div lh_card="true">
                <div lh_card_title="true">Новый товар</div>
                <form lh_product_editor="maker">
                    <xsl:apply-templates select=" . " mode="lh_product_fields" />
                    <div lh_block="true">
                        <button
                            lh_button="success"
                            type="submit"
                            disabled="disabled"
                            >
                            Создать товар
                        </button>
                        <a
                            lh_button="reset"
                            href="?product/list"
                            >
                            Отменить
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>