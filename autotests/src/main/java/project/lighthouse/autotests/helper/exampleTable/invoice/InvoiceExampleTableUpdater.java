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

    public ExamplesTable updateValuesStoredHorizontally() throws JSONException {
        return new ExampleTableUpdater(examplesTable)
                .updateValueStoredHorizontally("supplier", "{lastCreatedSupplierName}", Storage.getInvoiceVariableStorage().getSupplier().getName())
                .updateValueStoredHorizontally("name", "{lastCreatedProductName}", Storage.getInvoiceVariableStorage().getProduct().getName())
                .getExamplesTable();
    }

    public ExamplesTable updateValuesStoredVertically() throws JSONException {
        return new ExampleTableUpdater(examplesTable)
                .updateValueStoredVertically("supplier", "{lastCreatedSupplierName}", Storage.getInvoiceVariableStorage().getSupplier().getName())
                .updateValueStoredVertically("acceptanceDate", "{lastCreatedInvoiceAcceptanceDateValue}", Storage.getInvoiceVariableStorage().getAcceptanceDate())
                .updateValueStoredVertically("accepter", "{lastCreatedInvoiceAccepterValue}", Storage.getInvoiceVariableStorage().getAccepter())
                .updateValueStoredVertically("legalEntity", "{lastCreatedInvoiceLegalEntityValue}", Storage.getInvoiceVariableStorage().getLegalEntity())
                .updateValueStoredVertically("supplierInvoiceNumber", "{lastCreatedInvoiceSupplierInvoiceNumberValue}", Storage.getInvoiceVariableStorage().getSupplierInvoiceNumber())
                .getExamplesTable();
    }
}
