package project.lighthouse.autotests.steps.administrator;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.pages.administrator.users.UserApi;
import project.lighthouse.autotests.pages.administrator.users.UserCardPage;
import project.lighthouse.autotests.pages.administrator.users.UserCreatePage;
import project.lighthouse.autotests.pages.administrator.users.UsersListPage;

import java.io.IOException;

public class UserSteps extends ScenarioSteps {

    UserCreatePage userCreatePage;
    UserCardPage userCardPage;
    UsersListPage usersListPage;
    CommonPage commonPage;
    UserApi userApi;

    public UserSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        String updatedRole = userCreatePage.replaceSelectedValue(role);
        userApi.createUserThroughPost(name, position, login, password, updatedRole);
    }

    @Step
    public void navigateToTheUserPage(String login) throws JSONException {
        userApi.navigateToTheUserPage(login);
    }

    @Step
    public void userCreatePageOpen() {
        userCreatePage.open();
    }

    @Step
    public void userListPageOpen() {
        usersListPage.open();
    }

    @Step
    public void input(String elementName, String inputText) {
        userCreatePage.input(elementName, inputText);
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        userCreatePage.fieldInput(examplesTable);
    }

    @Step
    public void selectDropDown(String elementName, String value) {
        userCreatePage.selectByValue(elementName, value);
    }

    @Step
    public void userCreateButtonClick() {
        userCreatePage.userCreateButtonClick();
    }

    @Step
    public void checkCardValue(String elementName, String expectedValue) {
        userCardPage.checkCardValue(elementName, expectedValue);
    }

    @Step
    public void checkCardValue(ExamplesTable checkValuesTable) {
        userCardPage.checkCardValue(checkValuesTable);
    }

    @Step
    public void editButtonClick() {
        userCardPage.editButtonClick();
    }

    @Step
    public void createNewUserButtonClick() {
        usersListPage.createNewUserButtonClick();
    }

    @Step
    public void listItemClick(String login) {
        usersListPage.listItemClick(login);
    }

    @Step
    public void listItemCheck(String login) {
        usersListPage.listItemCheck(login);
    }

    @Step
    public void listItemCheckIsNotPresent(String value) {
        usersListPage.listItemCheckIsNotPresent(value);
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        usersListPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        userCreatePage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = commonPage.generateTestData(charNumber);
        input(elementName, generatedData);
    }

    @Step
    public void generateTestCharDataWithoutSpaces(String elementName, int charNumber, String str) {
        String generatedData = commonPage.generateTestDataWithoutWhiteSpaces(charNumber, str);
        input(elementName, generatedData);
    }

    @Step
    public void generateTestCharDataWithoutSpaces(String elementName, int charNumber) {
        String generatedData = commonPage.generateTestDataWithoutWhiteSpaces(charNumber);
        input(elementName, generatedData);
    }

    @Step
    public void backToTheUsersListPageLink() {
        userCreatePage.backToTheUsersListPageLink();
    }
}
