package project.lighthouse.autotests.fixtures.sprint_27;

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

public class Us_54_1_Fixture extends AbstractFixture {

    private static final String DATE_PATTERN = "dd.MM";
    private static final String YESTERDAY_DATE = new DateTimeHelper(1).convertDateByPattern(DATE_PATTERN);
    private static final String TWO_DAYS_AGO_DATE = new DateTimeHelper(2).convertDateByPattern(DATE_PATTERN);

    private static final String SHOP_NUMBER = "27541";
    private static final String PRODUCT_ID = "27541";
    private static final Double PRODUCT_PRICE_1 = 120.0;
    private static final Double PRODUCT_PRICE_2 = 125.0;

    public ExamplesTable prepareFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> stringMap = new HashMap<>();
        stringMap.put("date", YESTERDAY_DATE);
        stringMap.put("grossMargin", "11,23 р.");
        mapList.add(stringMap);
        stringMap.clear();
        stringMap.put("date", TWO_DAYS_AGO_DATE);
        stringMap.put("grossMargin", "33 333,33 р.");
        mapList.add(stringMap);
        return new ExamplesTable("").withRows(mapList);
    }

    public File getYesterdayPurchasesFixture() throws ParserConfigurationException, TransformerException, IOException, XPathExpressionException {
        return prepareDataFile(getYesterdayPurchases());
    }

    public File getTwoDaysAgoPurchasesFixture() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        return prepareDataFile(getTwoDaysAgoPurchases());
    }

    private PurchaseXmlBuilder getYesterdayPurchases() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(YESTERDAY_DATE, "00"),
                        YESTERDAY_DATE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_1 * 30),
                        Double.toString(PRODUCT_PRICE_1),
                        "1",
                        PRODUCT_ID);
    }

    private PurchaseXmlBuilder getTwoDaysAgoPurchases() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(YESTERDAY_DATE, "00"),
                        YESTERDAY_DATE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_2 * 25),
                        Double.toString(PRODUCT_PRICE_2),
                        "1",
                        PRODUCT_ID);
    }

    @Override
    public String getFixtureFileName() {
        return "s27u541";
    }
}
