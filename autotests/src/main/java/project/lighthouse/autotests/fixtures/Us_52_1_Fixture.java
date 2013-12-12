package project.lighthouse.autotests.fixtures;

import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class Us_52_1_Fixture extends AbstractFixture {

    private static final String SHOP_NUMBER = "25521";
    private static final String PRODUCT_ID1 = "2552129";
    private static final String PRODUCT_ID2 = "2552130";
    private static final String PRODUCT_ID3 = "2552131";

    public File prepareFirstProductData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return prepareReturnData("2013-10-03T12:45:24.235+04:00", "12.000", PRODUCT_ID1);
    }

    public File prepareSecondProductData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return prepareReturnData("2013-10-03T11:43:24.235+04:00", "85.560", PRODUCT_ID2);
    }

    public File prepareThirdProductData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return prepareReturnData("2013-10-03T09:43:24.235+04:00", "43.196", PRODUCT_ID3);
    }

    public File prepareReturnData(String dateSale, String count, String productID) throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlReturn(dateSale, "2013-10-03+04:00", SHOP_NUMBER, "1", "100", count, productID);
        return prepareDataFile(purchaseXmlBuilder);
    }

    @Override
    public String getFixtureFileName() {
        return "u25s521";
    }
}
