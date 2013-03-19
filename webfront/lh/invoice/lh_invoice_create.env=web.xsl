<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:template
        match=" *[ @lh_invoice_create ] "
        >
        <div lh_card_stack="true">
            <a
                lh_card_back="true"
                href="?invoice/list"
                >
                Накладные
            </a>
            
            <form
                lh_card="true"
                lh_invoice_edit="create"
                >
                
                <div name="{ name() }">
                    
                    <div lh_card_header="true">
                        <div lh_card_headerButtons="true">
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
                        <span lh_prop_value="true">
                            ООО «Рога и копыта»
                        </span>
                    </div>
                    
                    <br/>
                    <div lh_remark="true">
                        Необязательно к заполнению
                    </div>
                    
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
            
        </div>
    </xsl:template>
    
</xsl:stylesheet>