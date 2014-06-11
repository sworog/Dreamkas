package project.lighthouse.autotests.steps.administrator;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.deprecated.administrator.users.UserCreatePage;
import project.lighthouse.autotests.pages.deprecated.administrator.users.UsersListPage;
import project.lighthouse.autotests.pages.user.UserCardPage;
import project.lighthouse.autotests.pages.user.UserEditPage;
import project.lighthouse.autotests.pages.user.localNavigation.UserLocalNavigation;

import static org.junit.Assert.fail;

public class UserSteps extends ScenarioSteps {

    UserCreatePage userCreatePage;
    UserCardPage userCardPage;
    UsersListPage usersListPage;
    UserLocalNavigation userLocalNavigation;
    UserEditPage userEditPage;

    private ExamplesTable examplesTable;

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
        userEditPage.input(elementName, inputText);
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
        new PreLoader(getDriver()).await();
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
        userLocalNavigation.editButtonClick();
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
        userEditPage.checkFieldLength(elementName, fieldLength);
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
    public void pageBackLinkClick() {
        userCardPage.pageBackLinkClick();
    }

    @Step
    public void userCardEditButtonIsPresent() {
        userLocalNavigation.editButtonClick();
    }

    @Step
    public void userCardEditButtonIsNotPresent() {
        try {
            userLocalNavigation.editButtonClick();
            fail("User card edit link is present!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void userCardListLinkIsPresent() {
        userCardPage.pageBackLinkClick();
    }

    @Step
    public void userCardListLinkIsNotPresent() {
        try {
            userCardPage.pageBackLinkClick();
            fail("User card list link is present!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void logOutButtonClick() {
        userLocalNavigation.logOutButtonClick();
    }

    @Step
    public void saveButtonClick() {
        userEditPage.saveButtonClick();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void userEditPageInput(ExamplesTable examplesTable) {
        userEditPage.fieldInput(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void userEditPagePasswordInput(String password) {
        userEditPage.input("password", password);
    }

    @Step
    public void userEditPageInput(String elementName, String value) {
        userEditPage.input(elementName, value);
    }

    @Step
    public void userCardPageValuesCheck() {
        userCardPage.checkValues(examplesTable);
    }

    @Step
    public void userCardPageValueCheck(String elementName, String value) {
        userCardPage.checkValue(elementName, value);
    }

    @Step
    public void userEditPageOpen() {
        userEditPage.open();
    }
}
