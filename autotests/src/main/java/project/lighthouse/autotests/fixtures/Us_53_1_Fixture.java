package project.lighthouse.autotests.fixtures;

import org.apache.commons.io.FileUtils;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;
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
        Map<Integer, String> grossSalesPerHourMap = new HashMap<>();
        grossSalesPerHourMap.put(0, "124,5");
        grossSalesPerHourMap.put(1, "373.5");
        grossSalesPerHourMap.put(2, "747.0");
        grossSalesPerHourMap.put(3, "1245.0");
        grossSalesPerHourMap.put(4, "1867.5");
        grossSalesPerHourMap.put(5, "2614.5");
        grossSalesPerHourMap.put(6, "3486.0");
        grossSalesPerHourMap.put(7, "4482.0");
        grossSalesPerHourMap.put(8, "5602.5");
        grossSalesPerHourMap.put(9, "6847.5");
        grossSalesPerHourMap.put(10, "8217.0");
        grossSalesPerHourMap.put(11, "9711.0");
        grossSalesPerHourMap.put(12, "11329.5");
        grossSalesPerHourMap.put(13, "13072.5");
        grossSalesPerHourMap.put(14, "14940.0");
        grossSalesPerHourMap.put(15, "16932.0");
        grossSalesPerHourMap.put(16, "19048.5");
        grossSalesPerHourMap.put(17, "21289.5");
        grossSalesPerHourMap.put(18, "23655.0");
        grossSalesPerHourMap.put(19, "26145.0");
        grossSalesPerHourMap.put(20, "28759.5");
        grossSalesPerHourMap.put(21, "31498.5");
        grossSalesPerHourMap.put(22, "34362.0");
        grossSalesPerHourMap.put(23, "37350.0");
        return grossSalesPerHourMap;
    }

    private PurchaseXmlBuilder dataSet1(String date) throws ParserConfigurationException, XPathExpressionException {
        String shopNumber = "24531", price = "124,5", id = "24531";
        return new PurchaseXmlBuilder().createXmlPurchases("24")
                .addXmlPurchase(getDate(date, "01"), date, shopNumber, "124.5", price, "1", id)
                .addXmlPurchase(getDate(date, "02"), date, shopNumber, "249.0", price, "2", id)
                .addXmlPurchase(getDate(date, "03"), date, shopNumber, "373.5", price, "3", id)
                .addXmlPurchase(getDate(date, "04"), date, shopNumber, "498.0", price, "4", id)
                .addXmlPurchase(getDate(date, "05"), date, shopNumber, "622.5", price, "5", id)
                .addXmlPurchase(getDate(date, "06"), date, shopNumber, "747.0", price, "6", id)
                .addXmlPurchase(getDate(date, "07"), date, shopNumber, "871.5", price, "7", id)
                .addXmlPurchase(getDate(date, "08"), date, shopNumber, "996.0", price, "8", id)
                .addXmlPurchase(getDate(date, "09"), date, shopNumber, "1120.5", price, "9", id)
                .addXmlPurchase(getDate(date, "10"), date, shopNumber, "1245.0", price, "10", id)
                .addXmlPurchase(getDate(date, "11"), date, shopNumber, "1369.5", price, "11", id)
                .addXmlPurchase(getDate(date, "12"), date, shopNumber, "1494.0", price, "12", id)
                .addXmlPurchase(getDate(date, "13"), date, shopNumber, "1618.5", price, "13", id)
                .addXmlPurchase(getDate(date, "14"), date, shopNumber, "1743.0", price, "14", id)
                .addXmlPurchase(getDate(date, "15"), date, shopNumber, "1867.5", price, "15", id)
                .addXmlPurchase(getDate(date, "16"), date, shopNumber, "1992.0", price, "16", id)
                .addXmlPurchase(getDate(date, "17"), date, shopNumber, "2116.5", price, "17", id)
                .addXmlPurchase(getDate(date, "18"), date, shopNumber, "2241.0", price, "18", id)
                .addXmlPurchase(getDate(date, "19"), date, shopNumber, "2365.5", price, "19", id)
                .addXmlPurchase(getDate(date, "20"), date, shopNumber, "2490.0", price, "20", id)
                .addXmlPurchase(getDate(date, "21"), date, shopNumber, "2614.5", price, "21", id)
                .addXmlPurchase(getDate(date, "22"), date, shopNumber, "2739.0", price, "22", id)
                .addXmlPurchase(getDate(date, "23"), date, shopNumber, "2863.5", price, "23", id)
                .addXmlPurchase(getDate(date, "24"), date, shopNumber, "2988.0", price, "24", id);
    }

    public Map<Integer, String> getGrossSalesPerHourFromDataSet2() {
        Map<Integer, String> grossSalesPerHourMap = new HashMap<>();
        grossSalesPerHourMap.put(0, "174.5");
        grossSalesPerHourMap.put(1, "523.5");
        grossSalesPerHourMap.put(2, "1047.0");
        grossSalesPerHourMap.put(3, "1745.0");
        grossSalesPerHourMap.put(4, "2617.5");
        grossSalesPerHourMap.put(5, "3664.5");
        grossSalesPerHourMap.put(6, "4886.0");
        grossSalesPerHourMap.put(7, "6282.0");
        grossSalesPerHourMap.put(8, "7852.5");
        grossSalesPerHourMap.put(9, "9597.5");
        grossSalesPerHourMap.put(10, "11517.0");
        grossSalesPerHourMap.put(11, "13611.0");
        grossSalesPerHourMap.put(12, "15879.5");
        grossSalesPerHourMap.put(13, "18322.5");
        grossSalesPerHourMap.put(14, "20940.0");
        grossSalesPerHourMap.put(15, "23732.0");
        grossSalesPerHourMap.put(16, "26698.5");
        grossSalesPerHourMap.put(17, "29839.5");
        grossSalesPerHourMap.put(18, "33155.0");
        grossSalesPerHourMap.put(19, "36645.0");
        grossSalesPerHourMap.put(20, "40309.5");
        grossSalesPerHourMap.put(21, "44148.5");
        grossSalesPerHourMap.put(22, "48162.0");
        grossSalesPerHourMap.put(23, "52350.0");
        return grossSalesPerHourMap;
    }

    private PurchaseXmlBuilder dataSet2(String date) throws ParserConfigurationException, XPathExpressionException {
        String shopNumber = "24531", price = "174.5", id = "245311";
        return new PurchaseXmlBuilder().createXmlPurchases("24")
                .addXmlPurchase(getDate(date, "01"), date, shopNumber, "174.5", price, "2", id)
                .addXmlPurchase(getDate(date, "02"), date, shopNumber, "349.0", price, "3", id)
                .addXmlPurchase(getDate(date, "03"), date, shopNumber, "523.5", price, "4", id)
                .addXmlPurchase(getDate(date, "04"), date, shopNumber, "698.0", price, "5", id)
                .addXmlPurchase(getDate(date, "05"), date, shopNumber, "872.5", price, "6", id)
                .addXmlPurchase(getDate(date, "06"), date, shopNumber, "1047.0", price, "7", id)
                .addXmlPurchase(getDate(date, "07"), date, shopNumber, "1221.5", price, "8", id)
                .addXmlPurchase(getDate(date, "08"), date, shopNumber, "1396.0", price, "9", id)
                .addXmlPurchase(getDate(date, "09"), date, shopNumber, "1570.5", price, "10", id)
                .addXmlPurchase(getDate(date, "10"), date, shopNumber, "1745.0", price, "11", id)
                .addXmlPurchase(getDate(date, "11"), date, shopNumber, "1919.5", price, "12", id)
                .addXmlPurchase(getDate(date, "12"), date, shopNumber, "2094.0", price, "13", id)
                .addXmlPurchase(getDate(date, "13"), date, shopNumber, "2268.5", price, "14", id)
                .addXmlPurchase(getDate(date, "14"), date, shopNumber, "2443.0", price, "15", id)
                .addXmlPurchase(getDate(date, "15"), date, shopNumber, "2617.5", price, "16", id)
                .addXmlPurchase(getDate(date, "16"), date, shopNumber, "2792.0", price, "17", id)
                .addXmlPurchase(getDate(date, "17"), date, shopNumber, "2966.5", price, "18", id)
                .addXmlPurchase(getDate(date, "18"), date, shopNumber, "3141.0", price, "19", id)
                .addXmlPurchase(getDate(date, "19"), date, shopNumber, "3315.5", price, "20", id)
                .addXmlPurchase(getDate(date, "20"), date, shopNumber, "3490.0", price, "21", id)
                .addXmlPurchase(getDate(date, "21"), date, shopNumber, "3664.5", price, "22", id)
                .addXmlPurchase(getDate(date, "22"), date, shopNumber, "3839.0", price, "23", id)
                .addXmlPurchase(getDate(date, "23"), date, shopNumber, "4013.5", price, "24", id)
                .addXmlPurchase(getDate(date, "24"), date, shopNumber, "4188.0", price, "24", id);
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
