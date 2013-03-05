<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @mayak_product_editor ] "
        >
        <div mayak_card_stack="true">
            <a
                mayak_card_back="true"
                href="?product={ @id }"
                >
                <span mayak_card_titlePrefix="true">
                    <xsl:value-of select=" @sku " />
                </span>
                <span>
                    <xsl:value-of select=" @name " />
                </span>
            </a>
            <div mayak_card="true">
                <div mayak_card_title="true">Редактирование товара</div>
                <form mayak_product_editor="maker">
                    
                    <input
                        type="hidden"
                        name="id"
                        value="{ @id }"
                    />
                    
                    <xsl:apply-templates select=" . " mode="mayak_product_fields" />
                    
                    <div mayak_block="true">
                        <button
                            mayak_button="success"
                            type="submit"
                            disabled="disabled"
                            >
                            Сохранить данные
                        </button>
                        <a
                            mayak_button="reset"
                            href="?product={ @id }"
                            >
                            Отменить изменения
                        </a>
                    </div>
                    
                </form>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>