package ru.dreamkas.jbehave.store;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.steps.store.StoreSteps;

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

    @When("the user clicks on the close icon on the create new store modal window")
    public void whenTheUserClicksOnTheCloseIconOnTheCreateNewStoreModalWindow() {
        storeSteps.storeCreateModalWindowCloseIconClick();
    }

    @When("the user clicks on the close icon on the edit store modal window")
    public void whenTheUserClicksOnTheCloseIconOnTheEditStoreModalWindow() {
        storeSteps.storeEditModalWindowCloseIconClick();
    }

    @When("the user clicks on save button on the edit store modal window")
    public void whenTheUserClicksOnAddButtonOnTheEditStoreModalWindow() {
        storeSteps.storeEditModalWindowConfirmButtonClick();
    }

    @When("the user clicks on the store with name '$name'")
    public void whenTheUserClicksOnTheStoreWithName(String name) {
        storeSteps.storeObjectClickByName(name);
    }

    @When("the user generates symbols with count '$count' in the create store modal window field with name '$name'")
    public void whenTheUserGeneratesSymbolsWithCountInTheCreateStoreModalWindowFieldWithName(int count, String name) {
        storeSteps.storeCreateModalWindowGenerateString(name, count);
    }

    @When("the user generates symbols with count '$count' in the edit store modal window field with name '$name'")
    public void whenTheUserGeneratesSymbolsWithCountInTheEditStoreModalWindowFieldWithName(int count, String name) {
        storeSteps.storeEditModalWindowGenerateString(name, count);
    }
}
