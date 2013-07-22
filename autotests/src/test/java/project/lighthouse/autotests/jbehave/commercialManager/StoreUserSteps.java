package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.steps.AuthorizationSteps;
import project.lighthouse.autotests.steps.commercialManager.StoreSteps;

import java.io.IOException;

public class StoreUserSteps {

    ExamplesTable storeData;
    Store createdStore;

    @Steps
    StoreSteps formSteps;
    @Steps
    AuthorizationSteps authorizationSteps;

    @Given("the user is on create store page")
    public void userIsOnCreateStorePage() {
        formSteps.navigateToCreateStorePage();
    }

    @Given("user is on stores list page")
    public void userIsOnStoresListPage() {
        formSteps.navigateToStoresListPage();
    }

    @When("When user clicks create new store button")
    public void userClicksCreateNewStoreButton() {
        formSteps.clickCreateNewStoreButton();
    }

    @When("user clicks store form create button")
    public void userClicksFormCreateButton() {
        formSteps.clickCreateStoreSubmitButton();
    }

    @When("user clicks store form save button")
    public void userClicksFormSaveButton() {
        formSteps.clickSaveStoreSubmitButton();
    }

    @When("user fills store form with following data $formData")
    public void userFillsFormData(ExamplesTable formData) {
        formSteps.fillStoreFormData(formData);
        storeData = formData;
    }

    @Then("user checks store data in list")
    public void userChecksStoreDataInList() {
        formSteps.checkStoreDataInList(storeData);
    }

    @When("user clicks on store row in list")
    public void userClickOnStoreRowInList() {
        String storeNumber = storeData.getRow(0).get("value");
        formSteps.clickOnStoreRowInList(storeNumber);
    }

    @Then("user checks store card data")
    public void userChecksStoreCardData() {
        formSteps.checkStoreCardData(storeData);
    }

    @Given("there is created store with number '$number', address '$address', contacts '$contacts'")
    public void createStore(String number, String address, String contacts) throws IOException, JSONException {
        createdStore = formSteps.createStore(number, address, contacts);
    }

    @Given("user navigates to created store page")
    public void userNavigatesToCreatedStorePage() throws JSONException {
        formSteps.navigateToStorePage(createdStore.getId());
    }

    @When("user clicks edit button on store card page")
    public void userClicksEditButtonOnStoreCardPage() {
        formSteps.userClicksEditButtonOnStoreCardPage();
    }

    @Given("there is created store and user starts to edit it and fills form with $formData")
    public void thereIsCreatedStoreAndUserStartsToEditIt(ExamplesTable formData) throws IOException, JSONException {
        Store store = formSteps.createStore();
        formSteps.navigateToStorePage(store.getId());
        authorizationSteps.authorization("commercialManager");
        formSteps.userClicksEditButtonOnStoreCardPage();
        formSteps.fillStoreFormData(formData);
        formSteps.clickSaveStoreSubmitButton();
    }
}
