package project.lighthouse.autotests.fixtures.sprint_25;

import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.fixtures.AbstractFixture;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class Us_52_1_Fixture extends AbstractFixture {

    private static final String SHOP_NUMBER = "25521";
    private static final String PRODUCT_NAME1 = "name-2552129";
    private static final String PRODUCT_NAME2 = "name-2552130";
    private static final String PRODUCT_NAME3 = "name-2552131";

    public File prepareFirstProductData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return prepareReturnData("2013-10-03T12:45:24.235+04:00", "12.000", getProductSku(PRODUCT_NAME1));
    }

    public File prepareSecondProductData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return prepareReturnData("2013-10-03T11:43:24.235+04:00", "85.560", getProductSku(PRODUCT_NAME2));
    }

    public File prepareThirdProductData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return prepareReturnData("2013-10-03T09:43:24.235+04:00", "43.196", getProductSku(PRODUCT_NAME3));
    }

    public File prepareReturnData(String dateSale, String count, String productSku) throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlReturn(dateSale, "2013-10-03+04:00", SHOP_NUMBER, "1", "100", count, productSku);
        return prepareDataFile(purchaseXmlBuilder);
    }

    @Override
    public String getFixtureFileName() {
        return "u25s521";
    }
}
