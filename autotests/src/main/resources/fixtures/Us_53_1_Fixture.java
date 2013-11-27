package fixtures;

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

    public File prepareTodayData() throws ParserConfigurationException, XPathExpressionException, TransformerException, IOException, InterruptedException {
        String todayDate = new DateTimeHelper("today-0").convertDate();
        String shopNumber = "24531", price = "120.00", id = "24531";
        PurchaseXmlBuilder purchaseXmlBuilder = new PurchaseXmlBuilder().createXmlPurchases("24")
                .addXmlPurchase(getDate(todayDate, "01"), todayDate, shopNumber, "120.00", price, "1", id)
                .addXmlPurchase(getDate(todayDate, "02"), todayDate, shopNumber, "240.00", price, "2", id)
                .addXmlPurchase(getDate(todayDate, "03"), todayDate, shopNumber, "360.00", price, "3", id)
                .addXmlPurchase(getDate(todayDate, "04"), todayDate, shopNumber, "480.00", price, "4", id)
                .addXmlPurchase(getDate(todayDate, "05"), todayDate, shopNumber, "600.00", price, "5", id)
                .addXmlPurchase(getDate(todayDate, "06"), todayDate, shopNumber, "720.00", price, "6", id)
                .addXmlPurchase(getDate(todayDate, "07"), todayDate, shopNumber, "840.00", price, "7", id)
                .addXmlPurchase(getDate(todayDate, "08"), todayDate, shopNumber, "960.00", price, "8", id)
                .addXmlPurchase(getDate(todayDate, "09"), todayDate, shopNumber, "1080.00", price, "9", id)
                .addXmlPurchase(getDate(todayDate, "10"), todayDate, shopNumber, "1200.00", price, "10", id)
                .addXmlPurchase(getDate(todayDate, "11"), todayDate, shopNumber, "1320.00", price, "11", id)
                .addXmlPurchase(getDate(todayDate, "12"), todayDate, shopNumber, "1440.00", price, "12", id)
                .addXmlPurchase(getDate(todayDate, "13"), todayDate, shopNumber, "1560.00", price, "13", id)
                .addXmlPurchase(getDate(todayDate, "14"), todayDate, shopNumber, "1680.00", price, "14", id)
                .addXmlPurchase(getDate(todayDate, "15"), todayDate, shopNumber, "1800.00", price, "15", id)
                .addXmlPurchase(getDate(todayDate, "16"), todayDate, shopNumber, "1920.00", price, "16", id)
                .addXmlPurchase(getDate(todayDate, "17"), todayDate, shopNumber, "2040.00", price, "17", id)
                .addXmlPurchase(getDate(todayDate, "18"), todayDate, shopNumber, "2160.00", price, "18", id)
                .addXmlPurchase(getDate(todayDate, "19"), todayDate, shopNumber, "2280.00", price, "19", id)
                .addXmlPurchase(getDate(todayDate, "20"), todayDate, shopNumber, "2400.00", price, "20", id)
                .addXmlPurchase(getDate(todayDate, "21"), todayDate, shopNumber, "2520.00", price, "21", id)
                .addXmlPurchase(getDate(todayDate, "22"), todayDate, shopNumber, "2640.00", price, "22", id)
                .addXmlPurchase(getDate(todayDate, "23"), todayDate, shopNumber, "2760.00", price, "23", id)
                .addXmlPurchase(getDate(todayDate, "24"), todayDate, shopNumber, "2880.00", price, "24", id);
        File file = new File(System.getProperty("user.dir") + "/xml/out/u24s531s1.xml");
        FileUtils.writeStringToFile(file, purchaseXmlBuilder.asString());
        return file;
    }

    public Map<Integer, String> getGrossSalesPerHourMapForToday() {
        Map<Integer, String> grossSalesPerHourMap = new HashMap<>();
        grossSalesPerHourMap.put(0, "120");
        grossSalesPerHourMap.put(1, "360");
        grossSalesPerHourMap.put(2, "480");
        grossSalesPerHourMap.put(3, "600");
        grossSalesPerHourMap.put(4, "720");
        grossSalesPerHourMap.put(5, "840");
        grossSalesPerHourMap.put(6, "960");
        grossSalesPerHourMap.put(7, "1080");
        grossSalesPerHourMap.put(8, "1200");
        grossSalesPerHourMap.put(9, "1320");
        grossSalesPerHourMap.put(10, "1440");
        grossSalesPerHourMap.put(11, "1560");
        grossSalesPerHourMap.put(12, "1680");
        grossSalesPerHourMap.put(13, "1800");
        grossSalesPerHourMap.put(14, "1920");
        grossSalesPerHourMap.put(15, "2040");
        grossSalesPerHourMap.put(16, "2160");
        grossSalesPerHourMap.put(17, "2880");
        grossSalesPerHourMap.put(18, "3000");
        grossSalesPerHourMap.put(19, "3120");
        grossSalesPerHourMap.put(20, "3240");
        grossSalesPerHourMap.put(21, "3360");
        grossSalesPerHourMap.put(22, "3480");
        grossSalesPerHourMap.put(23, "3600");
        return grossSalesPerHourMap;
    }

    private String getDate(String date, String hour) {
        return date + "T" + hour + ":00:00.235+04:00";
    }

    public void prepareYesterdayData() {

    }

    public void prepareLastWeekData() {

    }
}
