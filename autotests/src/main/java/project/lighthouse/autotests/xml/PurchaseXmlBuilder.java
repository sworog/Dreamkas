package project.lighthouse.autotests.xml;

import com.jamesmurty.utils.XMLBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;

public class PurchaseXmlBuilder {

    private XMLBuilder xmlBuilder;

    public PurchaseXmlBuilder createXmlPurchases(String count) throws ParserConfigurationException {
        this.xmlBuilder = XMLBuilder.create("purchases").a("count", count);
        return this;
    }

    public PurchaseXmlBuilder addXmlPurchase(String saleTime, String operDay, String shop, String amount, String price, String count, String id) throws XPathExpressionException {
        xmlBuilder.xpathFind("//purchases")
                .e("purchase").a("discountAmount", "0.0").a("amount", amount).a("saletime", saleTime).a("number", "3").a("shift", "11").a("cash", "1")
                .a("shop", shop).a("operDay", operDay).a("operationType", "true").a("userName", "Админ Админ Админ").a("tabNumber", "123213123")
                .e("positions")
                .e("position").a("amount", amount).a("costWithDiscount", price).a("discountValue", "0.0").a("ndsSum", "0.0").a("nds", "0.0").a("cost", price)
                .a("count", count).a("barCode", id).a("goodsCode", id).a("departNumber", "1").a("order", "1")
                .up()
                .up()
                .e("payments")
                .e("payment")
                .a("description", "").a("amount", amount).a("typeClass", "CashPaymentEntity");
        return this;
    }

    public String asString() throws TransformerException {
        return xmlBuilder.asString();
    }
}
