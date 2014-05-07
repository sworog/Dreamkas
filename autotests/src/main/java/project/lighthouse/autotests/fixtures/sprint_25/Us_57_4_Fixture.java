package project.lighthouse.autotests.fixtures.sprint_25;

import org.jbehave.core.model.ExamplesTable;
import org.joda.time.DateTime;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.fixtures.AbstractFixture;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.xml.PurchaseXmlBuilder;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Us_57_4_Fixture extends AbstractFixture {

    private static final String SHOP_1 = "25574";
    private static final String SHOP_2 = "255742";
    private static final String PRODUCT_NAME = "name-255742";
    private static final String PRODUCT_NAME_2 = "name-255743";
    private static final String PRODUCT_BARCODE = "255742";
    private static final String PRODUCT_BARCODE_2 = "255743";
    private static final Double PRODUCT_PRICE_1 = 101.5;
    private static final Double PRODUCT_PRICE_2 = 102.5;
    private static final Double PRODUCT_PRICE_3 = 103.5;

    private final String todayDate = new DateTimeHelper(0).convertDate();
    private final String yesterdayDate = new DateTimeHelper(1).convertDate();
    private final String weekAgoDate = new DateTimeHelper(7).convertDate();

    public ExamplesTable getEmptyFixtureExampleTable() {
        return generateEmptyFixtureExampleTable("name-25574", getProductSku("name-25574"), "25574");
    }

    public ExamplesTable getFixtureExampleTableForCheckingDataIfProductBarcodeIsEmpty() {
        return generateEmptyFixtureExampleTable("name-255741", getProductSku("name-255741"), "");
    }

    @Override
    public String getFixtureFileName() {
        return "s25u574";
    }

    public File prepareTodayDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(todayDate, SHOP_1, getProductSku(PRODUCT_NAME), PRODUCT_PRICE_1);
    }

    public File prepareYesterdayDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(yesterdayDate, SHOP_1, getProductSku(PRODUCT_NAME), PRODUCT_PRICE_2);
    }

    public File prepareWeekAgoDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(weekAgoDate, SHOP_1, getProductSku(PRODUCT_NAME), PRODUCT_PRICE_3);
    }

    public File prepareTodayDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(todayDate, SHOP_2, getProductSku(PRODUCT_NAME_2), PRODUCT_PRICE_1);
    }

    public File prepareYesterdayDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(yesterdayDate, SHOP_2, getProductSku(PRODUCT_NAME_2), PRODUCT_PRICE_2);
    }

    public File prepareWeekAgoDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(weekAgoDate, SHOP_2, getProductSku(PRODUCT_NAME_2), PRODUCT_PRICE_3);
    }

    public ExamplesTable getExampleTableFixtureForProduct1() {
        return generateFixtureExampleTable(PRODUCT_NAME, getProductSku(PRODUCT_NAME), PRODUCT_BARCODE, getMapPrice1(), getMapPrice2(), getMapPrice3());
    }

    public ExamplesTable getExampleTableFixtureForProduct2() {
        return generateFixtureExampleTable(PRODUCT_NAME_2, getProductSku(PRODUCT_NAME_2), PRODUCT_BARCODE_2, getMapPrice1(), getMapPrice2(), getMapPrice3());
    }

    public ExamplesTable getEmptyExampleTableFixtureForProduct1() {
        return generateEmptyFixtureExampleTable(PRODUCT_NAME, getProductSku(PRODUCT_NAME), PRODUCT_BARCODE);
    }

    public ExamplesTable getEmptyExampleTableFixtureForProduct2() {
        return generateEmptyFixtureExampleTable(PRODUCT_NAME_2, getProductSku(PRODUCT_NAME_2), PRODUCT_BARCODE_2);
    }

    private ExamplesTable generateFixtureExampleTable(String name, String sku, String barCode,
                                                      Map<Integer, String> todayGrossSaleMap,
                                                      Map<Integer, String> yesterdayGrossSaleMap,
                                                      Map<Integer, String> weekAgoGrossSaleMap) {
        int hour = new DateTime().getHourOfDay();
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("productName", name);
        shop1DataMap.put("productSku", sku);
        shop1DataMap.put("productBarcode", barCode);
        shop1DataMap.put("todayValue", getFormattedValue(todayGrossSaleMap.get(hour)));
        shop1DataMap.put("yesterdayValue", getFormattedValue(yesterdayGrossSaleMap.get(hour)));
        shop1DataMap.put("weekAgoValue", getFormattedValue(weekAgoGrossSaleMap.get(hour)));
        mapList.add(shop1DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    private ExamplesTable generateEmptyFixtureExampleTable(String name, String sku, String barCode) {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("productName", name);
        shop1DataMap.put("productSku", sku);
        shop1DataMap.put("productBarcode", barCode);
        shop1DataMap.put("todayValue", "0,00 р.");
        shop1DataMap.put("yesterdayValue", "0,00 р.");
        shop1DataMap.put("weekAgoValue", "0,00 р.");
        mapList.add(shop1DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    public Map<Integer, String> getMapPrice1() {
        Map<Integer, Double> generatedData = generateGrossSalesSumPerHour(PRODUCT_PRICE_1);
        return generateFormattedGrossSalesSumPerHour(generatedData);
    }

    public Map<Integer, String> getMapPrice2() {
        Map<Integer, Double> generatedData = generateGrossSalesSumPerHour(PRODUCT_PRICE_2);
        return generateFormattedGrossSalesSumPerHour(generatedData);
    }

    public Map<Integer, String> getMapPrice3() {
        Map<Integer, Double> generatedData = generateGrossSalesSumPerHour(PRODUCT_PRICE_3);
        return generateFormattedGrossSalesSumPerHour(generatedData);
    }

    public File generateFileDataSet(String date, String shopNumber, String sku, Double price) throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        PurchaseXmlBuilder purchaseXmlBuilder = generateDataSet(date, shopNumber, sku, price);
        return prepareDataFile(purchaseXmlBuilder);
    }

    public TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet getTodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet() {
        return new TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet(SHOP_1, getProductSku(PRODUCT_NAME));
    }

    public class TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet extends DataSet {

        public TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet(String shopNumber, String productSku) {
            super(shopNumber, productSku);
        }

        public File prepareTodayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(todayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_3);
        }

        public File prepareYesterdayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(yesterdayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_3);
        }

        public File prepareWeekAgoData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(weekAgoDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_3);
        }
    }

    public TodayIsBiggerThanYesterdayAndWeekAgoDataSet getTodayIsBiggerThanYesterdayAndWeekAgoDataSet() {
        return new TodayIsBiggerThanYesterdayAndWeekAgoDataSet(SHOP_1, PRODUCT_NAME);
    }

    public class TodayIsBiggerThanYesterdayAndWeekAgoDataSet extends DataSet {

        public TodayIsBiggerThanYesterdayAndWeekAgoDataSet(String shopNumber, String productSku) {
            super(shopNumber, productSku);
        }

        public File prepareTodayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(todayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_3);
        }

        public File prepareYesterdayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(yesterdayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_2);
        }

        public File prepareWeekAgoData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(weekAgoDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_1);
        }
    }

    public TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet() {
        return new TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet(SHOP_1, getProductSku(PRODUCT_NAME));
    }

    public class TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet extends DataSet {

        public TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet(String shopNumber, String productSku) {
            super(shopNumber, productSku);
        }

        public File prepareTodayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(todayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_2);
        }

        public File prepareYesterdayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(yesterdayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_3);
        }

        public File prepareWeekAgoData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(weekAgoDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_1);
        }
    }

    public TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet getTodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet() {
        return new TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet(SHOP_1, PRODUCT_NAME);
    }

    public class TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet extends DataSet {

        public TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet(String shopNumber, String productSku) {
            super(shopNumber, productSku);
        }

        public File prepareTodayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(todayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_2);
        }

        public File prepareYesterdayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(yesterdayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_1);
        }

        public File prepareWeekAgoData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(weekAgoDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_3);
        }
    }

    public TodayIsSmallerThanYesterdayAndWeekAgoDataSet getTodayIsSmallerThanYesterdayAndWeekAgoDataSet() {
        return new TodayIsSmallerThanYesterdayAndWeekAgoDataSet(SHOP_1, getProductSku(PRODUCT_NAME));
    }

    public class TodayIsSmallerThanYesterdayAndWeekAgoDataSet extends DataSet {

        public TodayIsSmallerThanYesterdayAndWeekAgoDataSet(String shopNumber, String productSku) {
            super(shopNumber, productSku);
        }

        public File prepareTodayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(todayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_1);
        }

        public File prepareYesterdayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(yesterdayDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_2);
        }

        public File prepareWeekAgoData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
            return generateFileDataSet(weekAgoDate, getShopNumber(), getProductSku(), PRODUCT_PRICE_3);
        }
    }

    private class DataSet {

        private String shopNumber;
        private String productSku;

        public DataSet(String shopNumber, String productSku) {
            this.shopNumber = shopNumber;
            this.productSku = productSku;
        }

        public String getShopNumber() {
            return shopNumber;
        }

        public String getProductSku() {
            return productSku;
        }
    }
}
