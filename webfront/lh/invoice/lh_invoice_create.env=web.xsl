<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_application_view = 'lh_invoice_create' ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?invoice/list"
                >
                Накладные
            </a>
            
            <div lh_card="true">
                <xsl:apply-templates select=" html | error " mode="lh_error" />
                <xsl:apply-templates select=" . " mode="lh_invoice_create" />
            </div>
        </div>
    </xsl:template>
    
    <xsl:template
        match=" * "
        mode="lh_invoice_create"
        >
        <form lh_invoice_edit="create">
            <div name="invoice">
                
                <input type="hidden" name="sumTotal" value="0" />
                
                <div lh_card_header="true">
                    <div lh_card_tools="true">
                        <a
                            lh_card_close="true"
                            lh_link="close"
                            href="?invoice/list"
                            >
                            <xsl:apply-templates select="." mode="lh_link_close" />
                            закрыть
                        </a>
                    </div>
                    <span lh_card_title="true">
                        Приёмка № 
                    </span>
                    <span lh_field="short">
                        <input
                            lh_field_native="true"
                            name="sku"
                            value="{ sku }"
                        />
                    </span>
                    <span lh_field="inline">
                        от
                        <input
                            lh_field_native="true"
                            name="acceptanceDate"
                            value="{ acceptanceDate }"
                        />
                    </span>
                </div>
                
                <label lh_prop="true">
                    <span lh_prop_name="true">
                        Поставщик
                    </span>
                    <span lh_prop_value="true" lh_field="normal">
                        <input
                            lh_field_native="true"
                            name="supplier"
                            value="{ supplier }"
                        />
                    </span>
                </label>
                
                <label lh_prop="true">
                    <span lh_prop_name="true">
                        Кто принял
                    </span>
                    <span lh_prop_value="true" lh_field="normal">
                        <input
                            lh_field_native="true"
                            name="accepter"
                            value="{ accepter }"
                        />
                    </span>
                </label>
                
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        Получатель
                    </span>
                    <span lh_prop_value="true" lh_field="normal">
                        <input
                            lh_field_native="true"
                            name="legalEntity"
                            value="{ legalEntity }"
                        />
                    </span>
                </div>
                
                <label lh_prop="true">
                    <span lh_prop_name="true">
                    </span>
                    <div lh_remark="true">
                        Необязательно к заполнению
                    </div>
                </label>
                
                <div lh_prop="true">
                    <span lh_prop_name="true">
                        Входящий номер
                    </span>
                    <span lh_prop_value="true">
                        <span lh_field="short">
                            <input
                                lh_field_native="true"
                                name="supplierInvoiceSku"
                                value="{ supplierInvoiceSku }"
                            />
                        </span>
                        <span lh_field="inline">
                            от
                            <input
                                lh_field_native="true"
                                name="supplierInvoiceDate"
                                value="{ supplierInvoiceDate }"
                            />
                        </span>
                    </span>
                </div>
                
            </div>
            
            <div
                lh_card_foot="true"
                >
                <div
                    lh_prop="true"
                    >
                    <span lh_prop_name="true"></span>
                    <button
                        lh_button="commit"
                        type="submit"
                        disabled="disabled"
                        >
                        Сохранить и закрыть
                    </button>
                </div>
            </div>
            
        </form>
    </xsl:template>

</xsl:stylesheet>