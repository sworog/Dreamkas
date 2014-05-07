package project.lighthouse.autotests.fixtures.sprint_22;

import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.fixtures.AbstractFixture;
import project.lighthouse.autotests.fixtures.OldFixture;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class Us_40_3_Fixture extends AbstractFixture {

    final private static String SHOP_1 = "6666";
    final private static String SHOP_2 = "7777";

    @Override
    public String getFixtureFileName() {
        return "s22us403";
    }

    public File prepareReturnData(String productName) throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {
        String productSku = StaticData.products.get(productName).getSku();
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlReturn("2013-10-03T12:43:24.235+04:00", "2013-10-03+04:00", SHOP_1, "25.99", "26.99", "2.0", productSku)
                .addXmlReturn("2013-10-03T12:43:21.494+04:00", "2013-10-03+04:00", SHOP_2, "25.50", "25.50", "3.0", productSku)
                ;
        return prepareDataFile(purchaseXmlBuilder);
    }
}
