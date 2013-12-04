package project.lighthouse.autotests.fixtures;

import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.helper.DateTimeHelper;

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
    private static final String PRODUCT_ID = "dfdfdf";
    private static final Double PRODUCT_PRICE1 = 156.67;
    private static final Double PRODUCT_PRICE2 = 156.67;

    private final String yesterdayDate = new DateTimeHelper(1).convertDate();
    private final String twoDaysAgoDate = new DateTimeHelper(2).convertDate();
    private final String eightDaysAgo = new DateTimeHelper(8).convertDate();

    public ExamplesTable getFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("storeNumber", "245521");
        shop1DataMap.put("yesterdayValue", getGrossSalesSumOnTheEndOfTheDay());
        shop1DataMap.put("twoDaysAgoValue", getGrossSalesSumOnTheEndOfTheDay());
        shop1DataMap.put("eightDaysAgoValue", getGrossSalesSumOnTheEndOfTheDay());
        mapList.add(shop1DataMap);
        Map<String, String> shop2DataMap = new HashMap<>();
        shop1DataMap.put("storeNumber", "245522");
        shop1DataMap.put("yesterdayValue", getGrossSalesSumOnTheEndOfTheDay());
        shop1DataMap.put("twoDaysAgoValue", getGrossSalesSumOnTheEndOfTheDay());
        shop1DataMap.put("eightDaysAgoValue", getGrossSalesSumOnTheEndOfTheDay());
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
        shop1DataMap.put("storeNumber", "245522");
        shop1DataMap.put("yesterdayValue", "0,00 р.");
        shop1DataMap.put("twoDaysAgoValue", "0,00 р.");
        shop1DataMap.put("eightDaysAgoValue", "0,00 р.");
        mapList.add(shop2DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    public File prepareYesterdayDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(yesterdayDate, SHOP1, PRODUCT_ID, PRODUCT_PRICE1);
    }

    public File prepareTwoDaysAgoForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(twoDaysAgoDate, SHOP1, PRODUCT_ID, PRODUCT_PRICE1);
    }

    public File prepareEightDaysAgoDataForShop1() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(eightDaysAgo, SHOP1, PRODUCT_ID, PRODUCT_PRICE1);
    }

    public File prepareYesterdayDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(yesterdayDate, SHOP2, PRODUCT_ID, PRODUCT_PRICE2);
    }

    public File prepareTwoDaysAgoForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(twoDaysAgoDate, SHOP2, PRODUCT_ID, PRODUCT_PRICE2);
    }

    public File prepareEightDaysAgoDataForShop2() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return generateFileDataSet(eightDaysAgo, SHOP2, PRODUCT_ID, PRODUCT_PRICE2);
    }

    private String getGrossSalesSumOnTheEndOfTheDay() {
        return generateGrossSalesSumPerHour(PRODUCT_PRICE1).get(24);
    }

    @Override
    public String getFixtureFileName() {
        return "u24s552";
    }
}
