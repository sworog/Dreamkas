package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.StoreSteps;

public class StoreUserSteps {

    ExamplesTable storeData;

    @Steps
    StoreSteps formSteps;

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

    @When("user clicks form submit button")
    public void userClicksFormSubmitButton() {
        formSteps.clickCreateStoreSubmitButton();
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
}
