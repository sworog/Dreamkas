package project.lighthouse.autotests.fixtures;

import org.jbehave.core.model.ExamplesTable;
import org.joda.time.DateTime;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;
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

    public ExamplesTable getFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<>();
        for (int i = 0; i < new DateTime().getHourOfDay(); i++) {
            Map<String, String> stringMap = new HashMap<>();
            stringMap.put("date", String.format("%02d:00", i));
            stringMap.put("todayValue", new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(i + 1));
            stringMap.put("yesterdayValue", new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(i + 1));
            stringMap.put("weekAgoValue", new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(i + 1));
            mapList.add(stringMap);
        }
        return new ExamplesTable("").withRows(mapList);
    }
}
