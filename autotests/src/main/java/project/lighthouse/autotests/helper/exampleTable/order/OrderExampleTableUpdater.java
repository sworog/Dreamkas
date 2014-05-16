package project.lighthouse.autotests.helper.exampleTable.order;

import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.exampleTable.ExampleTableUpdater;
import project.lighthouse.autotests.storage.Storage;

public class OrderExampleTableUpdater {

    private ExamplesTable examplesTable;

    public OrderExampleTableUpdater(ExamplesTable examplesTable) {
        this.examplesTable = examplesTable;
    }

    public ExamplesTable updateValues() throws JSONException {
        return new ExampleTableUpdater(examplesTable)
                .updateValueStoredHorizontally("number", "{lastCreatedOrderNumber}", Storage.getOrderVariableStorage().getNumber())
                .updateValueStoredHorizontally("number", "{previousCreatedOrderNumber}", Storage.getOrderVariableStorage().getPreviousNumber())
                .updateValueStoredHorizontally("date", "{todayDate}", new DateTimeHelper(0).convertDateByPattern("dd.MM.yyyy"))
                .updateValueStoredHorizontally("supplier", "{lastSupplierOrder}", Storage.getOrderVariableStorage().getSupplier().getName())
                .updateValueStoredHorizontally("name", "{lastCreatedProductName}", Storage.getOrderVariableStorage().getProduct().getName())
                .getExamplesTable();
    }
}
