package project.lighthouse.autotests.steps.administrator;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.administrator.users.UserCardPage;
import project.lighthouse.autotests.pages.administrator.users.UserCreatePage;
import project.lighthouse.autotests.pages.administrator.users.UsersListPage;

import static org.junit.Assert.fail;

public class UserSteps extends ScenarioSteps {

    UserCreatePage userCreatePage;
    UserCardPage userCardPage;
    UsersListPage usersListPage;

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
        String generatedData = new StringGenerator(charNumber).generateTestData();
        input(elementName, generatedData);
    }

    @Step
    public void generateTestCharDataWithoutSpaces(String elementName, int charNumber, String str) {
        String generatedData = new StringGenerator(charNumber).generateTestDataWithoutWhiteSpaces(str);
        input(elementName, generatedData);
    }

    @Step
    public void generateTestCharDataWithoutSpaces(String elementName, int charNumber) {
        String generatedData = new StringGenerator(charNumber).generateTestDataWithoutWhiteSpaces();
        input(elementName, generatedData);
    }

    @Step
    public void backToTheUsersListPageLink() {
        userCreatePage.backToTheUsersListPageLink();
    }

    @Step
    public void userCardEditButtonIsPresent() {
        userCardPage.editButtonClick();
    }

    @Step
    public void userCardEditButtonIsNotPresent() {
        try {
            userCardPage.editButtonClick();
            fail("User card edit link is present!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void userCardListLinkIsPresent() {
        userCardPage.pageBackLink();
    }

    @Step
    public void userCardListLinkIsNotPresent() {
        try {
            userCardPage.pageBackLink();
            fail("User card list link is present!");
        } catch (Exception ignored) {
        }
    }
}
