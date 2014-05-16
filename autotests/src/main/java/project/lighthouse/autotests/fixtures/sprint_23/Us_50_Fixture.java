package project.lighthouse.autotests.fixtures.sprint_23;

import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.fixtures.AbstractFixture;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class Us_50_Fixture extends AbstractFixture {

    @Override
    public String getFixtureFileName() {
        return "s23u50";
    }

    public File createPositiveSalesXmlFile(int days) throws ParserConfigurationException, TransformerException, IOException, XPathExpressionException {
        String productSku1 = StaticData.products.get("Балык свиной в/с в/об Матера").getSku();
        String productSku2 = StaticData.products.get("Балык Ломберный с/к в/с ТД Рублевский").getSku();
        String productSku3 = StaticData.products.get("Ассорти Читтерио мясное нар.140г").getSku();

        String date = new DateTimeHelper(days).convertDate();
        String dateSale = date.concat("T12:43:24.235+04:00");
        String operDay = date.concat("+04:00");

        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlPurchase(dateSale, operDay, "2350", "344,08", "344,08", "5.0", productSku1)
                .addXmlPurchase(dateSale, operDay, "2350", "746,24", "746,24", "11.0", productSku2)
                .addXmlPurchase(dateSale, operDay, "2350", "494,56", "494,56", "34.0", productSku3)
                ;
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File createNegativeSalesXmlFile(int days, String productName) throws ParserConfigurationException, TransformerException, IOException, XPathExpressionException {
        String productSku = StaticData.products.get(productName).getSku();
        String date = new DateTimeHelper(days).convertDate();
        String dateSale = date.concat("T12:43:24.235+04:00");
        String operDay = date.concat("+04:00");

        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("1")
                .addXmlPurchase(dateSale, operDay, "2350", "100,00", "100,00", "10.0", productSku);
        return prepareDataFile(purchaseXmlBuilder);
    }
}