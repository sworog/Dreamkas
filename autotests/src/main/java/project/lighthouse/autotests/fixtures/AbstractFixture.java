package project.lighthouse.autotests.fixtures;

import org.apache.commons.io.FileUtils;
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

    public File generateFileDataSet(String date, String shopNumber, String id, Double price) throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        PurchaseXmlBuilder purchaseXmlBuilder = generateDataSet(date, shopNumber, id, price);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public Map<Integer, String> generateGrossSalesSumPerHour(Double price) {
        Map<Integer, String> grossSalesPerHourMap = new HashMap<>();
        Double grossSale = 0.0;
        for (int i = 1; i < 25; i++) {
            grossSale = grossSale + price * i;
            char groupSeparator = new DecimalFormatSymbols().getGroupingSeparator();
            String formattedGrossSaleValue = String.format("%1$,.2f", grossSale).replace(groupSeparator, ' ');
            grossSalesPerHourMap.put(i, formattedGrossSaleValue);
        }
        return grossSalesPerHourMap;
    }

    public Map<Integer, String> generateGrossSaleByHour(Double price) {
        Map<Integer, String> grossSalesByHourMap = new HashMap<>();
        for (int i = 1; i < 25; i++) {
            grossSalesByHourMap.put(i, getFormattedPriceValue(price * i));
        }
        return grossSalesByHourMap;
    }

    private PurchaseXmlBuilder generateDataSet(String date, String shopNumber, String id, Double price) throws ParserConfigurationException, XPathExpressionException {
        Double doublePrice = 124.5;
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("24");
        for (int i = 1; i < 25; i++) {
            Double finalPriceCount = doublePrice * i;
            String hours = String.format("%02d", i - 1);
            purchaseXmlBuilder.addXmlPurchase(getDate(date, hours), date, shopNumber, finalPriceCount.toString(), price.toString(), Integer.toString(i), id);
        }
        return purchaseXmlBuilder;
    }

    private String getDate(String date, String hour) {
        return date + "T" + hour + ":00:00.235+04:00";
    }

    private File prepareDataFile(PurchaseXmlBuilder purchaseXmlBuilder) throws TransformerException, IOException {
        return prepareDataFile(purchaseXmlBuilder, getFixtureFileName());
    }

    private File prepareDataFile(PurchaseXmlBuilder purchaseXmlBuilder, String fileName) throws TransformerException, IOException {
        String filePath = String.format("%s//xml/out/%s.xml", System.getProperty("user.dir"), fileName);
        File file = new File(filePath);
        FileUtils.writeStringToFile(file, purchaseXmlBuilder.asString());
        return file;
    }

    private String getFormattedPriceValue(Double priceValue) {
        char groupSeparator = new DecimalFormatSymbols().getGroupingSeparator();
        return String.format("%1$,.2f", priceValue).replace(groupSeparator, ' ');
    }


    abstract public String getFixtureFileName();
}
