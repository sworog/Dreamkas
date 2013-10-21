package project.lighthouse.autotests.objects;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class ProductInvoiceListObjectsList extends ArrayList<ProductInvoiceListObject> {

    public void compareWithExampleTable(ExamplesTable examplesTable) {

        List<Map<String, String>> notFoundRows = new ArrayList<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            for (ProductInvoiceListObject productInvoiceListObject : this) {
                if (productInvoiceListObject.rowIsEqual(row)) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                notFoundRows.add(row);
            }
        }
        if (notFoundRows.size() > 0) {
            String errorMessage = String.format("These rows are nt found: %s", notFoundRows.toArray());
            Assert.fail(errorMessage);
        }
    }
}
