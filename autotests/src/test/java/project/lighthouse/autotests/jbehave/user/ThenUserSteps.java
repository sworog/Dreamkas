package project.lighthouse.autotests.jbehave.user;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.administrator.UserSteps;

public class ThenUserSteps {

    @Steps
    UserSteps userSteps;

    @Then("the user checks the user with '$login' username is present")
    public void thenTheUSerChecksTheProductWithSkuIsPresent(String login) {
        userSteps.listItemCheck(login);
    }

    @Then("the user checks the user with '$login' username has '$name' element equal to '$expectedValue' on users page")
    public void thenTheUserChecksTheProductWithValueHasElement(String login, String elementName, String expectedValue) {
        userSteps.checkListItemHasExpectedValueByFindByLocator(login, elementName, expectedValue);
    }

    @Then("the user checks the user page '$elementName' value is '$expectedValue'")
    public void thenTheUserChecksValue(String elementName, String expectedValue) {
        userSteps.checkCardValue(elementName, expectedValue);
    }

    @Then("the user checks the user page elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(ExamplesTable checkValuesTable) {
        userSteps.checkCardValue(checkValuesTable);
    }

    @Then("the user checks '$elementName' user page field contains only '$number' symbols")
    public void thenTheUserChecksUserPageFieldContainsSymbols(String elementName, int number) {
        userSteps.checkFieldLength(elementName, number);
    }

    @Then("the user sees user card edit button")
    public void thenTheUserSeesUserCardEditButton() {
        userSteps.userCardEditButtonIsPresent();
    }

    @Then("the user sees no user card edit button")
    public void thenTheUserSeesNoUserCardEditButton() {
        userSteps.userCardEditButtonIsNotPresent();
    }

    @Then("the user sees user card link to users list")
    public void thenTheUserSeesUserCardLinkToUsersList() {
        userSteps.userCardListLinkIsPresent();
    }

    @Then("the user sees no user card link to users list")
    public void thenTheUserSeesNoUserCardLinkToUsersList() {
        userSteps.userCardListLinkIsNotPresent();
    }

    @Then("the user checks stored values on user card page")
    public void thenTheUserChecksStoredValuesOnUserCardPage() {
        userSteps.userCardPageValuesCheck();
    }

    @Then("the user asserts the element '$elementName' value is equal to value on user card page")
    public void thenTheUserAssertsTheElementValueIsEqualToValueOnUserCardPage(String elementName, String value) {
        userSteps.userCardPageValueCheck(elementName, value);
    }
}
