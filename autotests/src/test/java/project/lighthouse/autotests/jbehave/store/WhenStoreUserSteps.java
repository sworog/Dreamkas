package project.lighthouse.autotests.jbehave.store;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.store.StoreSteps;

public class WhenStoreUserSteps {

    @Steps
    StoreSteps storeSteps;

    @When("the user clicks on the add new store create button")
    public void whenTheUserClicksOnTheStoreCreateButton() {
        storeSteps.addStoreButtonClick();
    }

    @When("the user inputs values on the create new store modal window $examplesTable")
    public void whenTheUserInputsValuesOnTheCreateNewStoreModalWindow(ExamplesTable examplesTable) {
        storeSteps.storeCreateModalWindowInputValues(examplesTable);
    }

    @When("the user inputs values on the edit store modal window $examplesTable")
    public void whenTheUserInputsValuesOnTheEditStoreModalWindow(ExamplesTable examplesTable) {
        storeSteps.storeEditModalWindowInputValues(examplesTable);
    }

    @When("the user clicks on add button on the create new store modal window")
    public void whenTheUserClicksOnAddButtonOnTheCreateNewStoreModalWindow() {
        storeSteps.storeCreateModalWindowConfirmButtonClick();
    }

    @When("the user clicks on save button on the edit store modal window")
    public void whenTheUserClicksOnAddButtonOnTheEditStoreModalWindow() {
        storeSteps.storeEditModalWindowConfirmButtonClick();
    }

    @When("the user clicks on the store with name '$name'")
    public void whenTheUserClicksOnTheStoreWithName(String name) {
        storeSteps.storeObjectClickByName(name);
    }
}
