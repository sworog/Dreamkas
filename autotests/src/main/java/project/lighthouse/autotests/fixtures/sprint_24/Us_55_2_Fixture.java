package project.lighthouse.autotests.fixtures.sprint_24;

import org.jbehave.core.model.ExamplesTable;
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

public class Us_55_2_Fixture extends AbstractFixture {

    private static final String SHOP1 = "245521";
    private static final String SHOP2 = "245522";
    private static final String SHOP3 = "2455222";
    private static final String PRODUCT_NAME = "name-24552";
    private static final String PRODUCT_NAME2 = "name-245522";
    private static final Double PRODUCT_PRICE1 = 124.5;
    private static final Double PRODUCT_PRICE2 = 134.5;
    private static final Double PRODUCT_PRICE3 = 111.5;
    private static final Double PRODUCT_PRICE4 = 115.5;
    private static final Double PRODUCT_PRICE5 = 120.5;

    private final String yesterdayDate = new DateTimeHelper(1).convertDate();
    private final String twoDaysAgoDate = new DateTimeHelper(2).convertDate();
    private final String eightDaysAgoDate = new DateTimeHelper(8).convertDate();

    public ExamplesTable getFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("storeNumber", "245521");
        shop1DataMap.put("yesterdayValue", getGrossSalesSumOnTheEndOfTheDay());
        shop1DataMap.put("twoDaysAgoValue", getGrossSaleSumOnTheEndOfTheDay(PRODUCT_PRICE1));
        shop1DataMap.put("eightDaysAgoValue", getGrossSaleSumOnTheEndOfTheDay(PRODUCT_PRICE2));
        mapList.add(shop1DataMap);
        Map<String, String> shop2DataMap = new HashMap<>();
        shop2DataMap.put("storeNumber", "245522");
        shop2DataMap.put("yesterdayValue", getGrossSaleSumOnTheEndOfTheDay(PRODUCT_PRICE1));
        shop2DataMap.put("twoDaysAgoValue", getGrossSaleSumOnTheEndOfTheDay(PRODUCT_PRICE2));
        shop2DataMap.put("eightDaysAgoValue", getGrossSalesSumOnTheEndOfTheDay());
        mapList.add(shop2DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    public ExamplesTable getFixtureExampleTableToCheckZeroSale() {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("storeNumber", "245521");
        shop1DataMap.put("yesterdayValue", "0,00 р.");
        shop1DataMap.put("twoDaysAgoValue", "0,00 р.");
        shop1DataMap.put("eightDaysAgoValue", "0,00 р.");
        mapList.add(shop1DataMap);
        Map<String, String> shop2DataMap = new HashMap<>();
        shop2DataMap.put("storeNumber", "245522");
        shop2DataMap.put("yesterdayValue", "0,00 р.");
        shop2DataMap.put("twoDaysAgoValue", "0,00 р.");
        shop2DataMap.put("eightDaysAgoValue", "0,00 р.");
        mapList.add(shop2DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    public File prepareYesterdayDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithTwoProducts(yesterdayDate, SHOP1);
    }

    public File prepareTwoDaysAgoDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithOneProduct(twoDaysAgoDate, SHOP1, getProductSku(PRODUCT_NAME), PRODUCT_PRICE1);
    }

    public File prepareEightDaysAgoDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithOneProduct(eightDaysAgoDate, SHOP1, getProductSku(PRODUCT_NAME2), PRODUCT_PRICE2);
    }

    public File prepareYesterdayDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithOneProduct(yesterdayDate, SHOP2, getProductSku(PRODUCT_NAME), PRODUCT_PRICE1);
    }

    public File prepareTwoDaysAgoDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithOneProduct(twoDaysAgoDate, SHOP2, getProductSku(PRODUCT_NAME2), PRODUCT_PRICE2);
    }

    public File prepareEightDaysAgoDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithTwoProducts(eightDaysAgoDate, SHOP2);
    }

    public File prepareEightDaysAgoDataForShop3() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithOneProduct(eightDaysAgoDate, SHOP3, getProductSku(PRODUCT_NAME), PRODUCT_PRICE3);
    }

    public File prepareTwoDaysAgoDataForShop3() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithOneProduct(twoDaysAgoDate, SHOP3, getProductSku(PRODUCT_NAME), PRODUCT_PRICE4);
    }

    public File prepareYesterdayDataForShop3() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSetWithOneProduct(yesterdayDate, SHOP3, getProductSku(PRODUCT_NAME), PRODUCT_PRICE5);
    }

    private String getGrossSalesSumOnTheEndOfTheDay() {
        Map<Integer, Double> generatedData = generateGrossSalesSumPerHour();
        return getFormattedValue(generateFormattedGrossSalesSumPerHour(generatedData).get(24));
    }

    private Map<Integer, Double> generateGrossSalesSumPerHour() {
        Map<Integer, Double> grossSalesPerHourMap = new HashMap<>();
        Double productGrossSale1 = 0.0;
        Double productGrossSale2 = 0.0;
        for (int i = 1; i < 25; i++) {
            productGrossSale1 = productGrossSale1 + PRODUCT_PRICE1 * i;
            productGrossSale2 = productGrossSale2 + PRODUCT_PRICE2 * i;
            grossSalesPerHourMap.put(i, productGrossSale1 + productGrossSale2);
        }
        return grossSalesPerHourMap;
    }

    private String getGrossSaleSumOnTheEndOfTheDay(Double price) {
        return getFormattedValue(generateFormattedGrossSalesSumPerHour(generateGrossSalesSumPerHour(price)).get(24));
    }

    private File generateFileDataSetWithTwoProducts(String date, String shopNumber) throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        PurchaseXmlBuilder purchaseXmlBuilder = generateDataSetWithTwoProducts(date, shopNumber);
        return prepareDataFile(purchaseXmlBuilder);
    }

    private File generateFileDataSetWithOneProduct(String date, String shopNumber, String id, Double price) throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        PurchaseXmlBuilder purchaseXmlBuilder = generateDataSet(date, shopNumber, id, price);
        return prepareDataFile(purchaseXmlBuilder);
    }

    private PurchaseXmlBuilder generateDataSetWithTwoProducts(String date, String shopNumber) throws ParserConfigurationException, XPathExpressionException {
        PurchaseXmlBuilder purchaseXmlBuilder = PurchaseXmlBuilder.create("24");
        for (int i = 1; i < 25; i++) {
            Double finalPriceCount = PRODUCT_PRICE1 * i;
            Double finalPriceCount2 = PRODUCT_PRICE2 * i;
            String hours = String.format("%02d", i - 1);
            String dateTime = getDate(date, hours);
            purchaseXmlBuilder.addXmlPurchase(dateTime, date, shopNumber, finalPriceCount.toString(), PRODUCT_PRICE1.toString(), Integer.toString(i), getProductSku(PRODUCT_NAME));
            purchaseXmlBuilder.addXmlPurchase(dateTime, date, shopNumber, finalPriceCount2.toString(), PRODUCT_PRICE2.toString(), Integer.toString(i), getProductSku(PRODUCT_NAME2));
        }
        return purchaseXmlBuilder;
    }

    @Override
    public String getFixtureFileName() {
        return "u24s552";
    }
}
