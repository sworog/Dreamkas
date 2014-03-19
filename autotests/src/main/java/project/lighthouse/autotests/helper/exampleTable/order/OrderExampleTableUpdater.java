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
                .updateValue("number", "{number}", Storage.getOrderVariableStorage().getNumber().toString())
                .updateValue("date", "{todayDate}", new DateTimeHelper(0).convertDateByPattern("dd.MM.yyyy"))
                .updateValue("supplier", "{lastSupplierOrder}", Storage.getOrderVariableStorage().supplier.getName())
                .getExamplesTable();
    }
}
