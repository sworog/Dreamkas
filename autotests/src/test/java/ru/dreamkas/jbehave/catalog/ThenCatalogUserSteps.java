package ru.dreamkas.jbehave.catalog;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import ru.dreamkas.steps.catalog.CatalogSteps;

public class ThenCatalogUserSteps {

    @Steps
    CatalogSteps catalogSteps;

    @Then("the user asserts catalog title is '$title'")
    public void thenTheUserAssertsCatalogTitle(String title) {
        catalogSteps.assertCatalogTitle(title);
    }

    @Then("the user asserts group title is '$title'")
    public void thenTheUserAssertsChoosenGroupTitle(String title) {
        catalogSteps.assertGroupTitle(title);
    }

    @Then("the user asserts the groups list contain group with name '$name'")
    public void thenTheUserAssertsTheGroupListContainGroupWithName(String name) {
        catalogSteps.groupCollectionContainsGroupWithName(name);
    }

    @Then("the user asserts the groups list contain group with stored name")
    public void thenTheUserAssertsTheGroupListContainGroupWithName() {
        catalogSteps.groupCollectionContainsGroupWithStoredName();
    }

    @Then("the user asserts the groups list not contain group with name '$name'")
    public void thenTheUserAssertsTheGroupListNotContainGroupWithName(String name) {
        catalogSteps.groupCollectionNotContainGroupWithName(name);
    }

    @Then("the user asserts the create group modal window title is '$title'")
    public void thenTheUserAssertsTheCreateGroupModalWindowTitle(String title) {
        catalogSteps.assertCreateGroupModalPageTitle(title);
    }

    @Then("the user asserts the edit group modal window title is '$title'")
    public void thenTheUserAssertsTheEditGroupModalWindowTitle(String title) {
        catalogSteps.assertEditGroupModalPageTitle(title);
    }

    @Then("the user checks the create group modal windows name field has error message with text '$errorMessage'")
    public void thenTheUserChecksTheCreateGroupModalWindowNameFieldHasErrorMessageWithText(String errorMessage) {
        catalogSteps.createGroupModalPageCheckFieldError(errorMessage);
    }

    @Then("the user checks the edit group modal windows name field has error message with text '$errorMessage'")
    public void thenTheUserChecksTheEditGroupModalWindowNameFieldHasErrorMessageWithText(String errorMessage) {
        catalogSteps.editGroupModalPageCheckFieldError(errorMessage);
    }

    @Then("the user asserts the group title equals stored name")
    public void thenTheUserAssertStoredGroupTitle() {
        catalogSteps.assertStoredGroupTitle();
    }

    @Then("the user clicks on continue button in deleted group modal window")
    public void whenTheUserClicksOnContinueButtonInDeletedGroupModalWindow() {
        catalogSteps.deletedGroupModalPageContinueButtonClick();
    }
}
