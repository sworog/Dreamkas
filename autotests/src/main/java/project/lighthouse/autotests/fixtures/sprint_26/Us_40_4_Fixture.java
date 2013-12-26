package project.lighthouse.autotests.fixtures.sprint_26;

import project.lighthouse.autotests.fixtures.AbstractFixture;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class Us_40_4_Fixture extends AbstractFixture {

    private static final String SHOP_NUMBER = "26404";
    private static final String PRODUCT_ID = "26404";
    private static final Double PRODUCT_PRICE = 150.0;

    public File prepareSalePurchaseFile() throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {
        Double productPrice = PRODUCT_PRICE * 5;
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlPurchase(getDate(todayDate, "00"), todayDate, SHOP_NUMBER, productPrice.toString(), PRODUCT_PRICE.toString(), "5", PRODUCT_ID, 1);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareSalePurchaseReturnFile() throws TransformerException, IOException, ParserConfigurationException, XPathExpressionException {
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlPurchase(getDate(todayDate, "00"), todayDate, SHOP_NUMBER, PRODUCT_PRICE.toString(), PRODUCT_PRICE.toString(), "1", PRODUCT_ID, 1);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareReturnFile() throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {
        Double productPrice = PRODUCT_PRICE * 5;
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlReturn(getDate("2013-12-03", "00"), "2013-12-03+04:00", SHOP_NUMBER, productPrice.toString(), PRODUCT_PRICE.toString(), "5", PRODUCT_ID, 1);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareAnotherReturnFile() throws TransformerException, IOException, ParserConfigurationException, XPathExpressionException {
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlReturn(getDate("2013-12-03", "00"), "2013-12-03+04:00", SHOP_NUMBER, PRODUCT_PRICE.toString(), PRODUCT_PRICE.toString(), "1", PRODUCT_ID, 1);
        return prepareDataFile(purchaseXmlBuilder);
    }

    @Override
    public String getFixtureFileName() {
        return "u26s404";
    }
}
