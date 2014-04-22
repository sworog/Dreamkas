package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.When;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.InvoiceApiSteps;
import project.lighthouse.autotests.steps.departmentManager.invoice.deprecated.InvoiceSteps;

import java.io.IOException;

public class InvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Steps
    UserApiSteps userApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Steps
    StoreApiSteps storeApiSteps;

    @Given("the user is on the invoice list page")
    public void givenTheUserIsOnTheInvoiceListPage() throws IOException, JSONException {
        beforeSteps();
        invoiceSteps.openInvoiceListPage();
    }

    @Given("the user is on the store '$storeName' invoice list page")
    public void givenTheUserIsOnTheStoreInvoiceListPage(String storeName) throws JSONException {
        Store store = StaticData.stores.get(storeName);
        invoiceSteps.openStoreInvoiceListPage(store);
    }

    @Given("before steps")
    public void beforeSteps() throws IOException, JSONException {
        userApiSteps.getUser(InvoiceApiSteps.DEFAULT_USER_NAME);
        catalogApiSteps.promoteDepartmentManager(storeApiSteps.createStoreThroughPost(), InvoiceApiSteps.DEFAULT_USER_NAME);
    }

    @When("the user clicks OK and accepts changes")
    public void whenTheUSerClicksOkAndAcceptsChanges() throws InterruptedException {
        invoiceSteps.acceptChangesButtonClick();
    }

    @When("the user clicks Cancel and discard changes")
    public void whenTheUserClicksCancelAndDiscardTheChanges() {
        invoiceSteps.discardChangesButtonClick();
    }

    @When("the user clicks OK and accepts deletion")
    public void whenTheUSerClicksOkAndAcceptsDeletion() throws InterruptedException {
        invoiceSteps.acceptDeleteButtonClick();
    }

    @When("the user clicks Cancel and discard deletion")
    public void whenTheUserClicksCancelAndDiscardTheDeletion() {
        invoiceSteps.discardDeleteButtonClick();
    }

    //Search objects

    @When("the user clicks on item named '$itemName'")
    public void whenTheUserClicksOnItem(String itemName) {
        invoiceSteps.itemClick(itemName);
    }
}
