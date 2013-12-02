package project.lighthouse.autotests.fixtures;

import org.apache.commons.io.FileUtils;
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

public class Us_53_1_Fixture {

    public File prepareTodayDataFromDataSet1() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        String todayDate = new DateTimeHelper(0).convertDate();
        PurchaseXmlBuilder purchaseXmlBuilder = dataSet1(todayDate);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareYesterdayDataFromDataSet1() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        String todayDate = new DateTimeHelper(1).convertDate();
        PurchaseXmlBuilder purchaseXmlBuilder = dataSet1(todayDate);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareLastWeekDataFromDataSet1() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        String todayDate = new DateTimeHelper(7).convertDate();
        PurchaseXmlBuilder purchaseXmlBuilder = dataSet1(todayDate);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareTodayDataFromDataSet2() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        String todayDate = new DateTimeHelper(0).convertDate();
        PurchaseXmlBuilder purchaseXmlBuilder = dataSet2(todayDate);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareYesterdayDataFromDataSet2() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        String todayDate = new DateTimeHelper(1).convertDate();
        PurchaseXmlBuilder purchaseXmlBuilder = dataSet2(todayDate);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public File prepareLatWeekDataFromDataSet2() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        String todayDate = new DateTimeHelper(7).convertDate();
        PurchaseXmlBuilder purchaseXmlBuilder = dataSet2(todayDate);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public Map<Integer, String> getGrossSalesPerHourFromDataSet1() {
        Double price = 124.5;
        return generateGrossSalesPerHour(price);
    }

    private PurchaseXmlBuilder dataSet1(String date) throws ParserConfigurationException, XPathExpressionException {
        String shopNumber = "24531", id = "24531";
        Double doublePrice = 124.5;
        return generateDataSet(date, shopNumber, id, doublePrice);
    }

    public Map<Integer, String> getGrossSalesPerHourFromDataSet2() {
        Double price = 174.5;
        return generateGrossSalesPerHour(price);
    }

    private PurchaseXmlBuilder dataSet2(String date) throws ParserConfigurationException, XPathExpressionException {
        String shopNumber = "24531", id = "245311";
        Double price = 174.5;
        return generateDataSet(date, shopNumber, id, price);
    }

    private Map<Integer, String> generateGrossSalesPerHour(Double price) {
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

    private PurchaseXmlBuilder generateDataSet(String date, String shopNumber, String id, Double price) throws ParserConfigurationException, XPathExpressionException {
        Double doublePrice = 124.5;
        PurchaseXmlBuilder purchaseXmlBuilder = new PurchaseXmlBuilder().createXmlPurchases("24");
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

    private File prepareDataFile(PurchaseXmlBuilder purchaseXmlBuilder, String fileName) throws TransformerException, IOException {
        String filePath = String.format("%s//xml/out/%s.xml", System.getProperty("user.dir"), fileName);
        File file = new File(filePath);
        FileUtils.writeStringToFile(file, purchaseXmlBuilder.asString());
        return file;
    }

    private File prepareDataFile(PurchaseXmlBuilder purchaseXmlBuilder) throws TransformerException, IOException {
        return prepareDataFile(purchaseXmlBuilder, "u24s531s1");
    }
}
