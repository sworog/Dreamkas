package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

public class GivenInvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Given("the user opens the store '$storeNumber' invoice create page")
    public void givenTheUserOpensTheStoreInvoiceCreatePage(String storeNumber) throws JSONException {
        Store store = StaticData.stores.get(storeNumber);
        invoiceSteps.openStoreInvoiceCreatePage(store);
    }
}
