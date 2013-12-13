package project.lighthouse.autotests.fixtures;

import org.jbehave.core.model.ExamplesTable;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Us_57_4_Fixture extends AbstractFixture {

    public ExamplesTable getFixtureExampleTable() {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("productName", "'name-25574");
        shop1DataMap.put("productSku", "25574");
        shop1DataMap.put("productBarcode", "25574");
        shop1DataMap.put("todayValue", "0,00 р.");
        shop1DataMap.put("yesterdayValue", "0,00 р.");
        shop1DataMap.put("weekAgoValue", "0,00 р.");
        mapList.add(shop1DataMap);
        return new ExamplesTable("").withRows(mapList);
    }

    @Override
    public String getFixtureFileName() {
        return "s25u574";
    }
}
