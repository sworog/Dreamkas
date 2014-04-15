package project.lighthouse.autotests.helper.exampleTable.invoice;

import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.helper.exampleTable.ExampleTableUpdater;
import project.lighthouse.autotests.storage.Storage;

public class InvoiceExampleTableUpdater {

    private ExamplesTable examplesTable;

    public InvoiceExampleTableUpdater(ExamplesTable examplesTable) {
        this.examplesTable = examplesTable;
    }

    public ExamplesTable updateValues() throws JSONException {
        return new ExampleTableUpdater(examplesTable)
                .updateValue("supplier", "{lastCreatedSupplierName}", Storage.getOrderVariableStorage().getSupplier().getName())
                .updateValue("name", "{lastCreatedProductName}", Storage.getInvoiceVariableStorage().getProduct().getName())
                .getExamplesTable();
    }
}
