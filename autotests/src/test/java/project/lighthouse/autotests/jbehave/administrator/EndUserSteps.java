package project.lighthouse.autotests.jbehave.administrator;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.administrator.UserSteps;

import java.io.IOException;

public class EndUserSteps {

    @Steps
    UserSteps userSteps;

    @Given("the user opens create new user page")
    public void givenTheUserOpensCreateNewUserPage() {
        userSteps.userCreatePageOpen();
    }

    @Given("there is the user with name '$name', position '$position', username '$userName', password '$password', role '$role'")
    @Alias("there is the user with name '$name', position '$position', <userName>, password '$password', role '$role'")
    public void givenThereIsTheUser(String name, String position, String userName, String password, String role) throws IOException, JSONException {
        userSteps.createUserThroughPost(name, position, userName, password, role);
    }

    @Given("the user navigates to the user page with username '$login'")
    public void givenTheUserNavigatesToTheUserPage(String login) throws JSONException {
        userSteps.navigateToTheUserPage(login);
    }

    @Given("the user is on the users list page")
    public void givenTheUserIsOnTheUsersListPage() {
        userSteps.userListPageOpen();
    }

    @When("the user clicks the create new user button from users list page")
    public void whenTheUserCreatesNewUserFromUsersListPage() {
        userSteps.createNewUserButtonClick();
    }

    @When("the user inputs '$inputText' in the user page '$elementName' field")
    public void whenTheUserInputsInTheUserPageField(String inputText, String elementName) {
        userSteps.input(elementName, inputText);
    }

    @When("the user inputs values in the user page element fields $fieldInputTable")
    public void whenTheUserInputsInElementFields(ExamplesTable fieldInputTable) {
        userSteps.input(fieldInputTable);
    }

    @When("the user selects '$value' in the user page'$elementName' dropdown")
    public void whenTheUserSelectsValueInTheUserPageDropDown(String value, String elementName) {
        userSteps.selectDropDown(elementName, value);
    }

    @When("the user clicks the create new user button")
    public void whenTheUserClicksTheCreateNewUserButton() {
        userSteps.userCreateButtonClick();
    }

    @When("the user clicks the edit button on the user card page")
    public void whenTheUserClicksTheEditButtonOnTheUserCardPage() {
        userSteps.editButtonClick();
    }

    @When("the user opens the user card with '$login' username")
    public void whenTheUserOpensTheUserCard(String login) {
        userSteps.listItemClick(login);
    }

    @When("the user clicks on the users list page link")
    public void whenTheUserClicksOnTheUserListPageLink() {
        userSteps.backToTheUsersListPageLink();
    }

    @When("the user generates charData with '$number' number in the '$elementName' user page field")
    public void whenTheUserGeneratesCharDataWithNumber(int number, String elementName) {
        userSteps.generateTestCharData(elementName, number);
    }

    @When("the user generates charData with '$number' number without spaces in the '$elementName' user page field")
    public void whenTheUserGeneratesCharDataWithNumberWithoutSpaces(int number, String elementName) {
        userSteps.generateTestCharDataWithoutSpaces(elementName, number);
    }

    @When("the user generates charData with '$number' number without spaces and '$str' char in the '$elementName' user page field")
    public void whenTheUserGeneratesCharDataWithNumberWithoutSpaces(int number, String str, String elementName) {
        userSteps.generateTestCharDataWithoutSpaces(elementName, number, str);
    }

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
}
