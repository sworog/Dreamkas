<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_product_editor ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?product={ id }"
                >
                <span lh_card_titlePrefix="true">
                    <xsl:value-of select=" sku " />
                </span>
                <span>
                    <xsl:value-of select=" name " />
                </span>
            </a>
            <div lh_card="true">
                <div lh_card_title="true">Редактирование товара</div>
                <form lh_product_editor="maker">
                    
                    <xsl:apply-templates select=" . " mode="lh_product_fields" />
                    
                    <div lh_block="true">
                        <button
                            lh_button="success"
                            type="submit"
                            disabled="disabled"
                            >
                            Сохранить данные
                        </button>
                        <a
                            lh_button="reset"
                            href="?product={ id }"
                            >
                            Отменить изменения
                        </a>
                    </div>
                    
                </form>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>