package project.lighthouse.autotests.jbehave.user;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.administrator.UserSteps;

public class WhenUserSteps {

    @Steps
    UserSteps userSteps;

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
        userSteps.pageBackLinkClick();
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

    @When("the user clicks on save user data button click")
    public void whenTheUserClicksOnSaveUserDataButtonClick() {
        userSteps.saveButtonClick();
    }

    @When("the user inputs values on user edit page $examplesTable")
    public void whenTheUserInputsValuesOnUserEditPage(ExamplesTable examplesTable) {
        userSteps.userEditPageInput(examplesTable);
    }

    @When("the user inputs password '$password' on the login page")
    public void whenTheUserInputsPasswordOnTheLoginPage(String password) {
        userSteps.userEditPagePasswordInput(password);
    }

    @When("the user inputs value '$value' in the field with elementName '$elementName'")
    public void whenTheUserInputsValueInTheFieldWithElementName(String value, String elementName) {
        userSteps.userEditPageInput(elementName, value);
    }
}
