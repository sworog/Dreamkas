package project.lighthouse.autotests.xml;

import com.jamesmurty.utils.XMLBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;

/**
 * Get new instance {@link #create(String)}
 */
public class PurchaseXmlBuilder {

    final public static String OPERATION_TYPE_SALE = "true";
    final public static String OPERATION_TYPE_RETURN = "false";

    private XMLBuilder xmlBuilder;

    protected int number = 1;

    private PurchaseXmlBuilder(String purchasesCount) throws ParserConfigurationException {
        this.xmlBuilder = XMLBuilder.create("purchases").a("count", purchasesCount);
    }

    public static PurchaseXmlBuilder create(String purchasesCount) throws ParserConfigurationException {
        return new PurchaseXmlBuilder(purchasesCount);
    }

    public PurchaseXmlBuilder addXmlPurchase(
            String saleTime,
            String operDay,
            String shop,
            String amount,
            String price,
            String count,
            String sku,
            Integer number
    ) throws XPathExpressionException {
        return xmlAction(saleTime, operDay, shop, amount, price, count, sku, OPERATION_TYPE_SALE, number);
    }

    public PurchaseXmlBuilder addXmlPurchase(
            String saleTime,
            String operDay,
            String shop,
            String amount,
            String price,
            String count,
            String sku
    ) throws XPathExpressionException {
        return xmlAction(saleTime, operDay, shop, amount, price, count, sku, OPERATION_TYPE_SALE, number++);
    }

    public PurchaseXmlBuilder addXmlReturn(
            String saleTime,
            String operDay,
            String shop,
            String amount,
            String price,
            String count,
            String sku,
            Integer number
    ) throws XPathExpressionException {
        return xmlAction(saleTime, operDay, shop, amount, price, count, sku, OPERATION_TYPE_RETURN, number);
    }

    public PurchaseXmlBuilder addXmlReturn(
            String saleTime,
            String operDay,
            String shop,
            String amount,
            String price,
            String count,
            String sku
    ) throws XPathExpressionException {
        return xmlAction(saleTime, operDay, shop, amount, price, count, sku, OPERATION_TYPE_RETURN, number++);
    }

    private PurchaseXmlBuilder xmlAction(
            String saleTime,
            String operDay,
            String shop,
            String amount,
            String price,
            String count,
            String sku,
            String operationType,
            Integer number
    ) throws XPathExpressionException {
        xmlBuilder.xpathFind("//purchases")
                .e("purchase")
                    .a("discountAmount", "0.0")
                    .a("amount", amount)
                    .a("saletime", saleTime)
                    .a("number", number.toString())
                    .a("shift", "11")
                    .a("cash", "1")
                    .a("shop", shop)
                    .a("operDay", operDay)
                    .a("operationType", operationType)
                    .a("userName", "Админ Админ Админ")
                    .a("tabNumber", "123213123")
                .e("positions")
                    .e("position")
                        .a("amount", amount)
                        .a("costWithDiscount", price)
                        .a("discountValue", "0.0")
                        .a("ndsSum", "0.0")
                        .a("nds", "0.0")
                        .a("cost", price)
                        .a("count", count)
                        .a("barCode", sku)
                        .a("goodsCode", sku)
                        .a("departNumber", "1")
                        .a("order", "1")
                    .up()
                .up()
                .e("payments")
                    .e("payment")
                        .a("description", "")
                        .a("amount", amount)
                        .a("typeClass", "CashPaymentEntity");
        return this;
    }

    public String asString() throws TransformerException {
        return xmlBuilder.asString();
    }
}
