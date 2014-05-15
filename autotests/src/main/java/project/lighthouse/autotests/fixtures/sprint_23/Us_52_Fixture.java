package project.lighthouse.autotests.fixtures.sprint_23;

import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.fixtures.AbstractFixture;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class Us_52_Fixture extends AbstractFixture {

    public File getImportSaleDataFixture() throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {

        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("2")
                .addXmlPurchase("2013-10-03T12:43:24.235+04:00", "2013-10-03+04:00", "2352", "252.99", "252.99", "2.363", StaticData.products.get("Черемша").getSku());
        return prepareDataFile(purchaseXmlBuilder);
    }

    @Override
    public String getFixtureFileName() {
        return "s32u52";
    }
}
