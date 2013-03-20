<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_application_view = 'lh_dashboard' ] "
        >
        <div lh_card_stack="true">
            <div lh_card="true">
                <div lh_card_header="true">
                    <span lh_card_title="true">
                        Пульт управления коммерческого директора
                    </span>
                </div>
                <div lh_block="true">
                    <a href="?product/list" lh_button="true">Товары</a>
                </div>
            </div>
        </div>
        <div lh_card_stack="true">
            <div lh_card="true">
                <div lh_card_header="true">
                    <span lh_card_title="true">
                        Консоль товароведа
                    </span>
                </div>
                <div lh_block="true">
                    <a href="?invoice/list" lh_button="true">Накладные</a>
                </div>
            </div>
        </div>
    </xsl:template>
    
</xsl:stylesheet>