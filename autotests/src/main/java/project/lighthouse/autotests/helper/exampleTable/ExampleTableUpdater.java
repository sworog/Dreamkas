package project.lighthouse.autotests.helper.exampleTable;

import org.jbehave.core.model.ExamplesTable;

import java.util.List;
import java.util.Map;

public class ExampleTableUpdater {

    private ExamplesTable examplesTable;

    public ExampleTableUpdater(ExamplesTable examplesTable) {
        this.examplesTable = examplesTable;
    }

    public ExampleTableUpdater updateValue(String key, String pattern, String newValue) {
        List<Map<String, String>> rows = examplesTable.getRows();
        for (Map<String, String> row : rows) {
            if (row.containsValue(pattern)) {
                row.put(key, newValue);
            }
        }
        this.examplesTable = new ExamplesTable("").withRows(rows);
        return this;
    }

    public ExamplesTable getExamplesTable() {
        return examplesTable;
    }
}
