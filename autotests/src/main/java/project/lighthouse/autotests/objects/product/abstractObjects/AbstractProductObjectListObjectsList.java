package project.lighthouse.autotests.objects.product.abstractObjects;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class AbstractProductObjectListObjectsList extends ArrayList<AbstractProductObjectList> {

    public void compareWithExampleTable(ExamplesTable examplesTable) {

        List<Map<String, String>> notFoundRows = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            for (AbstractProductObjectList abstractProductObjectList : this) {
                if (abstractProductObjectList.rowIsEqual(row)) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                notFoundRows.add(row);
            }
        }
        if (notFoundRows.size() > 0) {
            String errorMessage = String.format("These rows are not found: '%s'.", notFoundRows.toString());
            Assert.fail(errorMessage);
        }
    }
}
