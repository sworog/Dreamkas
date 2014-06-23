package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.commercialManager.StoreSteps;

import java.io.IOException;

public class EndStoreApiSteps {

    Store createdStore;

    @Steps
    StoreApiSteps storeApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Steps
    StoreSteps storeSteps;

    @Given("there is created store with number '$number', address '$address', contacts '$contacts'")
    public void createStore(String number, String address, String contacts) throws IOException, JSONException {
        createdStore = storeApiSteps.createStoreThroughPost(number, address, contacts);
    }

    @Given("there is the store with number '$storeNumber'")
    public void givenThereIsTheStoreWithNumber(String storeNumber) throws IOException, JSONException {
        createdStore = storeApiSteps.createStoreThroughPost(storeNumber, storeNumber, storeNumber);
    }

    @Deprecated
    @Given("there is the store with number '$storeNumber' managed by '$userName'")
    @Alias("there is the store with <storeNumber> managed by <userName>")
    public void givenThereIsTheStoreManagedBy(String storeNumber, String userName) throws IOException, JSONException {
        createdStore = storeApiSteps.createStoreThroughPost(storeNumber, storeNumber, storeNumber);
        catalogApiSteps.promoteStoreManager(createdStore, userName);
    }

    @Deprecated
    @Given("there is the store with number '$storeNumber' managed by department manager named '$userName'")
    public void givenThereIsTheStoreManagedByDepartmentManager(String storeNumber, String userName) throws IOException, JSONException {
        createdStore = storeApiSteps.createStoreThroughPost(storeNumber, storeNumber, storeNumber);
        catalogApiSteps.promoteDepartmentManager(createdStore, userName);
    }

    @Given("user navigates to created store page")
    public void userNavigatesToCreatedStorePage() throws JSONException {
        storeSteps.navigateToStorePage(createdStore.getId());
    }

    @Given("the user navigates to the store with number '$storeNumber'")
    public void givenTheUserNavigatesToTheStore(String storeNumber) throws JSONException {
        String storeId = storeApiSteps.getStoreId(storeNumber);
        storeSteps.navigateToStorePage(storeId);
    }

    @Given("the user with email '$email' creates the store with number '$number'")
    public void givenThereIsTheStoreWithNumberManagerByUserName(String email, String number) throws IOException, JSONException {
        storeApiSteps.createStoreThroughPostByUserWithEmail(number, number, number, email);
    }
}
