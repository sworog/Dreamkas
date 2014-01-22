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
    private static final String YESTERDAY_DATE_PURCHASE = new DateTimeHelper(1).convertDate();
    private static final String TWO_DAYS_AGO_DATE = new DateTimeHelper(2).convertDateByPattern(DATE_PATTERN);
    private static final String TWO_DAYS_AGO_DATE_PURCHASE = new DateTimeHelper(2).convertDate();

    private static final String SHOP_NUMBER = "27541";
    private static final String PRODUCT_ID = "27541";
    private static final Double PRODUCT_PRICE_1 = 120.0;
    private static final Double PRODUCT_PRICE_2 = 125.0;

    public ExamplesTable prepareFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", YESTERDAY_DATE);
                        put("grossMarginSum", "850,00 р.");
                    }
                });
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", TWO_DAYS_AGO_DATE);
                        put("grossMarginSum", "875,00 р.");
                    }
                });
            }
        };
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
                        getDate(YESTERDAY_DATE_PURCHASE, "10"),
                        YESTERDAY_DATE_PURCHASE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_1 * 30),
                        Double.toString(PRODUCT_PRICE_1),
                        "30",
                        PRODUCT_ID);
    }

    private PurchaseXmlBuilder getTwoDaysAgoPurchases() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(TWO_DAYS_AGO_DATE_PURCHASE, "10"),
                        TWO_DAYS_AGO_DATE_PURCHASE, SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE_2 * 25),
                        Double.toString(PRODUCT_PRICE_2),
                        "25",
                        PRODUCT_ID);
    }

    @Override
    public String getFixtureFileName() {
        return "s27u541";
    }
}
