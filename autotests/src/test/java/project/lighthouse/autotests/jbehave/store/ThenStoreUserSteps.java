package project.lighthouse.autotests.jbehave.store;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.store.StoreSteps;

public class ThenStoreUserSteps {

    @Steps
    StoreSteps storeSteps;

    @Then("the user asserts the store list contain store with values $examplesTable")
    public void thenTheUserAssertsTheStoreListContainStoreWithStoredValues(ExamplesTable examplesTable) {
        storeSteps.storeObjectCollectionCompareWithExampleTable(examplesTable);
    }

    @Then("the user asserts the store list do not contain store with name '$name'")
    public void thenTheUserAssertsTheStoreListContainStoreWithName(String name) {
        storeSteps.storeObjectCollectionDoNotContainStoreWithName(name);
    }

    @Then("the user asserts the create new store modal window title is '$title'")
    public void thenTheUserAssertsTheCreateNewStoreModalWindowTitle(String title) {
        storeSteps.assertStoreCreateModalWindowTitle(title);
    }

    @Then("the user asserts the edit store modal window title is '$title'")
    public void thenTheUserAssertsTheEditStoreModalWindowTitle(String title) {
        storeSteps.assertStoreEditModalWindowTitle(title);
    }

    @Then("the user asserts stores list page title is '$title'")
    public void thenTheUserAssertsTheStoreListPageTitle(String title) {
        storeSteps.assertStoresListPageTitle(title);
    }
}
