package project.lighthouse.autotests.helper.exampleTable;

import org.jbehave.core.model.ExamplesTable;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * The class is used for example table conversion. It reverts the example table data
 */
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
