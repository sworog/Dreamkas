package project.lighthouse.autotests.fixtures.sprint_28;

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

public class Us_54_4_Fixture extends AbstractFixture {

    private static final String DATE_PATTERN = "dd.MM";
    private static final String YESTERDAY_DATE = new DateTimeHelper(1).convertDateByPattern(DATE_PATTERN);
    private static final String YESTERDAY_DATE_PURCHASE = new DateTimeHelper(1).convertDate();
    private static final String TWO_DAYS_AGO_DATE = new DateTimeHelper(2).convertDateByPattern(DATE_PATTERN);
    private static final String TWO_DAYS_AGO_DATE_PURCHASE = new DateTimeHelper(2).convertDate();

    private static final String SHOP_NUMBER = "28544";
    private static final String PRODUCT_ID = "28544";
    private static final String PRODUCT_ID_2 = "285441";
    private static final Double PRODUCT_PRICE_1 = 120.0;
    private static final Double PRODUCT_PRICE_2 = 130.0;
    private static final Double PRODUCT_PRICE_3 = 145.0;

    public ExamplesTable prepareFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", YESTERDAY_DATE);
                        put("grossMarginSum", "80,00 р.");
                    }
                });
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", TWO_DAYS_AGO_DATE);
                        put("grossMarginSum", "150,00 р.");
                    }
                });
            }
        };
        return new ExamplesTable("").withRows(mapList);
    }

    public ExamplesTable prepareFixtureExampleTableForSaleDuplicationWithUpdatedPriceChecking() {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", YESTERDAY_DATE);
                        put("grossMarginSum", "110,00 р.");
                    }
                });
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", TWO_DAYS_AGO_DATE);
                        put("grossMarginSum", "150,00 р.");
                    }
                });
            }
        };
        return new ExamplesTable("").withRows(mapList);
    }

    public ExamplesTable prepareFixtureExampleTableForSaleDuplicationWithUpdatedProductChecking() {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", YESTERDAY_DATE);
                        put("grossMarginSum", "60,00 р.");
                    }
                });
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", TWO_DAYS_AGO_DATE);
                        put("grossMarginSum", "150,00 р.");
                    }
                });
            }
        };
        return new ExamplesTable("").withRows(mapList);
    }

    public File getYesterdayPurchasesDuplicationFixtureWithUpdatedPrice() throws ParserConfigurationException, TransformerException, IOException, XPathExpressionException {
        return prepareDataFile(getYesterdayPurchasesDuplicationWithUpdatedPrice());
    }

    public File getYesterdayPurchasesDuplicationFixtureWithUpdatedProduct() throws ParserConfigurationException, TransformerException, IOException, XPathExpressionException {
        return prepareDataFile(getYesterdayPurchasesDuplicationWithUpdatedProduct());
    }


    public File getYesterdayPurchasesFixture() throws ParserConfigurationException, TransformerException, IOException, XPathExpressionException {
        return prepareDataFile(getYesterdayPurchases());
    }

    public File getTwoDaysAgoPurchasesFixture() throws XPathExpressionException, ParserConfigurationException, TransformerException, IOException {
        return prepareDataFile(getTwoDaysAgoPurchases());
    }

    private PurchaseXmlBuilder getYesterdayPurchasesDuplicationWithUpdatedPrice() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(YESTERDAY_DATE_PURCHASE, "10"),
                        YESTERDAY_DATE_PURCHASE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_3 * 2),
                        Double.toString(PRODUCT_PRICE_3),
                        "2",
                        PRODUCT_ID,
                        1
                );
    }

    private PurchaseXmlBuilder getYesterdayPurchasesDuplicationWithUpdatedProduct() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(YESTERDAY_DATE_PURCHASE, "10"),
                        YESTERDAY_DATE_PURCHASE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_2 * 2),
                        Double.toString(PRODUCT_PRICE_2),
                        "2",
                        PRODUCT_ID_2,
                        1
                );
    }

    private PurchaseXmlBuilder getYesterdayPurchases() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(YESTERDAY_DATE_PURCHASE, "10"),
                        YESTERDAY_DATE_PURCHASE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_2 * 2),
                        Double.toString(PRODUCT_PRICE_2),
                        "2",
                        PRODUCT_ID,
                        1
                );
    }

    private PurchaseXmlBuilder getTwoDaysAgoPurchases() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(TWO_DAYS_AGO_DATE_PURCHASE, "10"),
                        TWO_DAYS_AGO_DATE_PURCHASE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_1 * 5),
                        Double.toString(PRODUCT_PRICE_1),
                        "5",
                        PRODUCT_ID,
                        1
                );
    }

    @Override
    public String getFixtureFileName() {
        return "s28u544";
    }

    public static String getYesterdayDate() {
        return YESTERDAY_DATE;
    }

    public static String getTwoDaysAgoDate() {
        return TWO_DAYS_AGO_DATE;
    }
}
