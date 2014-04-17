package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.InvoiceApiSteps;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

import java.io.IOException;

public class GivenInvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Steps
    UserApiSteps userApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Steps
    StoreApiSteps storeApiSteps;

    @Given("the user opens the store '$storeNumber' invoice create page")
    public void givenTheUserOpensTheStoreInvoiceCreatePage(String storeNumber) throws JSONException {
        Store store = StaticData.stores.get(storeNumber);
        invoiceSteps.openStoreInvoiceCreatePage(store);
    }

    @Given("the user opens the default store invoice create page")
    public void givenTheUserOpensTheDefaultStoreInvoiceCreatePage() throws IOException, JSONException {
        beforeSteps();
        givenTheUserOpensTheStoreInvoiceCreatePage(Store.DEFAULT_NUMBER);
    }

    @Given("before steps")
    public void beforeSteps() throws IOException, JSONException {
        userApiSteps.getUser(InvoiceApiSteps.DEFAULT_USER_NAME);
        catalogApiSteps.promoteDepartmentManager(storeApiSteps.createStoreThroughPost(), InvoiceApiSteps.DEFAULT_USER_NAME);
    }
}
