package project.lighthouse.autotests.helper;

import org.jbehave.core.model.ExamplesTable;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class ExampleTableConverter {

    public static ExamplesTable convert(ExamplesTable examplesTable) {
        Map<String, String> map = new HashMap<>();
        for (int i = 0; i < examplesTable.getRowCount(); i++) {
            map.put(examplesTable.getRow(i).get("elementName"), examplesTable.getRow(i).get("elementValue"));
        }
        List<Map<String, String>> mapList = new ArrayList<>();
        mapList.add(map);
        return new ExamplesTable("").withRows(mapList);
    }
}
