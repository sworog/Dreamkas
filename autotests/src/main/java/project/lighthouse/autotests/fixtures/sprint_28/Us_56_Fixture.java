package project.lighthouse.autotests.fixtures.sprint_28;

import org.jbehave.core.model.ExamplesTable;
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

public class Us_56_Fixture extends Us_54_4_Fixture {

    private static final Double PRODUCT_PRICE = 125.0;
    private static final String PRODUCT_NAME = "name-28544";
    private static final String SHOP_NUMBER = "2856";

    public ExamplesTable prepareFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", getYesterdayDate());
                        put("grossMarginSum", "260,00 р.");
                    }
                });
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", getTwoDaysAgoDate());
                        put("grossMarginSum", "150,00 р.");
                    }
                });
            }
        };
        return new ExamplesTable("").withRows(mapList);
    }

    public File getYesterdayPurchasesFixture() throws ParserConfigurationException, TransformerException, IOException, XPathExpressionException {
        return prepareDataFile(getYesterdayPurchases());
    }

    private PurchaseXmlBuilder getYesterdayPurchases() throws ParserConfigurationException, XPathExpressionException {
        return PurchaseXmlBuilder.create("1")
                .addXmlPurchase(
                        getDate(getYesterdayDatePurchase(), "10"),
                        getYesterdayDatePurchase(), SHOP_NUMBER,
                        Double.toString(PRODUCT_PRICE * 6),
                        Double.toString(PRODUCT_PRICE),
                        "6",
                        getProductSku(PRODUCT_NAME),
                        1
                );
    }
}
