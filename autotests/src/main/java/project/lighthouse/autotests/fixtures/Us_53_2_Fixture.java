package project.lighthouse.autotests.fixtures;

import org.jbehave.core.model.ExamplesTable;
import org.joda.time.DateTime;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;
import java.text.DecimalFormatSymbols;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Us_53_2_Fixture {

    public File prepareTodayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return new Us_53_1_Fixture().prepareTodayDataFromDataSet1();
    }

    public File prepareYesterdayData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return new Us_53_1_Fixture().prepareYesterdayDataFromDataSet1();
    }

    public File prepareWeekAgoData() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        return new Us_53_1_Fixture().prepareLastWeekDataFromDataSet1();
    }

    public Map<Integer, String> getGrossSaleByHour() {
        Double price = 124.50;
        return generateGrossSaleByHour(price);
    }

    public ExamplesTable getFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<>();
        for (int i = 0; i < new DateTime().getHourOfDay(); i++) {
            Map<String, String> stringMap = new HashMap<>();
            stringMap.put("date", String.format("%02d:00 — %02d:00", i, i + 1));
            stringMap.put("todayValue", getFormattedValue(getGrossSaleByHour().get(i + 1)));
            stringMap.put("yesterdayValue", getFormattedValue(getGrossSaleByHour().get(i + 1)));
            stringMap.put("weekAgoValue", getFormattedValue(getGrossSaleByHour().get(i + 1)));
            mapList.add(stringMap);
        }
        return new ExamplesTable("").withRows(mapList);
    }

    private Map<Integer, String> generateGrossSaleByHour(Double price) {
        Map<Integer, String> grossSalesByHourMap = new HashMap<>();
        for (int i = 1; i < 25; i++) {
            grossSalesByHourMap.put(i, getFormattedPriceValue(price * i));
        }
        return grossSalesByHourMap;
    }

    private String getFormattedValue(String value) {
        return String.format("%s р.", value);
    }

    private String getFormattedPriceValue(Double priceValue) {
        char groupSeparator = new DecimalFormatSymbols().getGroupingSeparator();
        return String.format("%1$,.2f", priceValue).replace(groupSeparator, ' ');
    }
}
