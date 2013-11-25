package project.lighthouse.autotests.steps.departmentManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.helper.ExampleTableConverter;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffListPage;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffLocalNavigation;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffPage;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffSearchPage;

public class WriteOffSteps extends ScenarioSteps {

    WriteOffPage writeOffPage;
    CommonPage commonPage;
    WriteOffListPage writeOffListPage;
    WriteOffSearchPage writeOffSearchPage;
    WriteOffLocalNavigation writeOffLocalNavigation;

    public static ExamplesTable examplesTable;

    public WriteOffSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void openPage() {
        writeOffPage.open();
    }

    @Step
    public void input(String elementName, String inputText) {
        writeOffPage.input(elementName, inputText);
    }

    @Step
    public void continueWriteOffCreation() {
        writeOffPage.continueWriteOffCreation();
    }

    @Step
    public void addProductToWriteOff() {
        writeOffPage.addProductToWriteOff();
    }

    @Step
    public void checkCardValue(String elementName, String expectedValue) {
        writeOffPage.checkCardValue(elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String checkType, String elementName, String expectedValue) {
        writeOffPage.checkCardValue(checkType, elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String checkType, ExamplesTable checkValuesTable) {
        writeOffPage.checkCardValue(checkType, checkValuesTable);
    }

    @Step
    public void itemCheck(String value) {
        writeOffPage.itemCheck(value);
    }

    @Step
    public void itemCheckIsNotPresent(String value) {
        writeOffPage.itemCheckIsNotPresent(value);
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        writeOffPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocator(String value, ExamplesTable checkValuesTable) {
        writeOffPage.checkListItemHasExpectedValueByFindByLocator(value, checkValuesTable);
    }

    @Step
    public void itemDelete(String value) {
        writeOffPage.itemDelete(value);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = commonPage.generateTestData(charNumber);
        input(elementName, generatedData);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        writeOffPage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void writeOffStopEditButtonClick() {
        writeOffPage.writeOffStopEditButtonClick();
    }

    @Step
    public void writeOffStopEditlinkClick() {
        writeOffPage.writeOffStopEditlinkClick();
    }

    @Step
    public void editButtonClick() {
        writeOffPage.editButtonClick();
    }

    @Step
    public void elementClick(String elementName) {
        writeOffPage.elementClick(elementName);
    }

    @Deprecated
    @Step
    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        writeOffPage.childrentItemClickByFindByLocator(parentElementName, elementName);
    }

    @Step
    public void writeOffListPageOpen() {
        writeOffListPage.open();
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocatorInList(String value, String elementName, String expectedValue) {
        writeOffListPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Step
    public void listItemCheck(String value) {
        writeOffListPage.listItemCheck(value);
    }

    @Step
    public void writeOffItemListCreate() {
        writeOffListPage.writeOffItemListCreate();
    }

    @Step
    public void searchLinkClick() {
        writeOffLocalNavigation.searchLinkClick();
    }

    @Step
    public void writeOffSearch(String number) {
        writeOffSearchPage.input("number", number);
    }

    @Step
    public void searchButtonClick() {
        writeOffSearchPage.searchButtonClick();
    }

    public void createInvoiceLinkClick() {
        writeOffLocalNavigation.createInvoiceLinkClick();
    }

    @Step
    public void writeOffSearchResultClick(String number) {
        writeOffSearchPage.getWriteOffSearchObjectCollection().clickByLocator(number);
    }

    @Step
    public void writeOffSearchResultCheck(String number) {
        writeOffSearchPage.getWriteOffSearchObjectCollection().contains(number);
    }

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        writeOffSearchPage.getWriteOffSearchObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void compareWithExampleTable() {
        writeOffSearchPage.getWriteOffSearchObjectCollection().compareWithExampleTable(ExampleTableConverter.convert(examplesTable));
    }

    @Step
    public void writeOffHighLightTextCheck(String expectedHighLightedText) {
        writeOffSearchPage.getWriteOffSearchObjectCollection().containsHighLightText(expectedHighLightedText);
    }

    @Step
    public void clickPropertyByLocator(String locator, String propertyName) {
        writeOffPage.getWriteOffProductCollection().clickPropertyByLocator(locator, propertyName);
    }

    @Step
    public void inputPropertyByLocator(String locator, String propertyName, String value) {
        writeOffPage.getWriteOffProductCollection().inputPropertyByLocator(locator, propertyName, value);
    }
}
