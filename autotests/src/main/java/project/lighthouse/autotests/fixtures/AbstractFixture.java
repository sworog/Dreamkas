package project.lighthouse.autotests.fixtures;

import org.apache.commons.io.FileUtils;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;
import java.text.DecimalFormatSymbols;
import java.util.HashMap;
import java.util.Map;

public abstract class AbstractFixture {

    public final String todayDate = new DateTimeHelper(0).convertDate();
    public final String yesterdayDate = new DateTimeHelper(1).convertDate();
    public final String weekAgoDate = new DateTimeHelper(7).convertDate();

    public String getProductSku(String name) {
        return StaticData.products.get(name).getSku();
    }

    public Map<Integer, String> generateFormattedGrossSalesSumPerHour(Map<Integer, Double> fixtureMap) {
        Map<Integer, String> formattedMap = new HashMap<>();
        for (Map.Entry<Integer, Double> entry : fixtureMap.entrySet()) {
            formattedMap.put(entry.getKey(), getFormattedValue(entry.getValue()));
        }
        return formattedMap;
    }

    public Map<Integer, Double> generateGrossSalesSumPerHour(Double price) {
        Map<Integer, Double> grossSalesPerHourMap = new HashMap<>();
        Double grossSale = 0.0;
        for (int i = 1; i < 25; i++) {
            grossSale = grossSale + price * i;
            grossSalesPerHourMap.put(i, grossSale);
        }
        return grossSalesPerHourMap;
    }

    public Map<Integer, String> generateGrossSaleByHour(Double price) {
        Map<Integer, String> grossSalesByHourMap = new HashMap<>();
        for (int i = 1; i < 25; i++) {
            grossSalesByHourMap.put(i, getFormattedValue(price * i));
        }
        return grossSalesByHourMap;
    }

    public PurchaseXmlBuilder generateDataSet(String date, String shopNumber, String id, Double price) throws ParserConfigurationException, XPathExpressionException {
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("24");
        for (int i = 1; i < 25; i++) {
            Double finalPriceCount = price * i;
            String hours = String.format("%02d", i - 1);
            String dateTime = getDate(date, hours);
            purchaseXmlBuilder.addXmlPurchase(dateTime, date, shopNumber, finalPriceCount.toString(), price.toString(), Integer.toString(i), id);
        }
        return purchaseXmlBuilder;
    }

    public String getDate(String date, String hour) {
        return date + "T" + hour + ":00:00.235+04:00";
    }

    public File prepareDataFile(PurchaseXmlBuilder purchaseXmlBuilder) throws TransformerException, IOException {
        return prepareDataFile(purchaseXmlBuilder, getFixtureFileName());
    }

    private File prepareDataFile(PurchaseXmlBuilder purchaseXmlBuilder, String fileName) throws TransformerException, IOException {
        String filePath = String.format("%s//xml/out/%s.xml", System.getProperty("user.dir"), fileName);
        File file = new File(filePath);
        FileUtils.writeStringToFile(file, purchaseXmlBuilder.asString());
        return file;
    }

    private String getFormattedValue(Double priceValue) {
        char groupSeparator = new DecimalFormatSymbols().getGroupingSeparator();
        return String.format("%1$,.2f", priceValue).replace(groupSeparator, ' ');
    }

    public String getFormattedValue(String value) {
        return String.format("%s р.", value);
    }

    abstract public String getFixtureFileName();
}
