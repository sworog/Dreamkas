package project.lighthouse.autotests.fixtures.sprint_20;

import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.fixtures.AbstractFixture;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class Us_40_Fixture extends AbstractFixture {

    private static final String DATE_SALE = "2013-10-03T12:43:24.235+04:00";
    private static final String OPER_DAY = "2013-10-03+04:00";

    public void positiveXmlPurchasesDataCopyToCentrum() throws TransformerException, IOException, ParserConfigurationException, XPathExpressionException, InterruptedException {
        String productSku = StaticData.products.get("Конф.жев.Фруттелла 4 вкуса 42.5г").getSku();

        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("2")
                .addXmlPurchase(DATE_SALE, OPER_DAY, "666", "25.99", "25.99", "12.0", productSku)
                .addXmlPurchase(DATE_SALE, OPER_DAY, "777", "25.50", "25.50", "6.0", productSku);
        copyDataFileToCentrum(
                prepareDataFile(purchaseXmlBuilder));
    }

    public File getFixtureFileWithNoSuchProduct() throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {

        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("2")
                .addXmlPurchase(DATE_SALE, OPER_DAY, "666", "25.99", "25.99", "12.0", "871085219077834");
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File getFixtureFileWithNoExistStore() throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException {

        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("2")
                .addXmlPurchase(DATE_SALE, OPER_DAY, "3455453453", "25.99", "25.99", "12.0", "871085219077834");
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File getFixtureFileWithCorruptedData() {
        return getFileFixture("purchases-data-corrupted.xml");
    }

    @Override
    public String getFixtureFileName() {
        return "s20u40s1";
    }
}
