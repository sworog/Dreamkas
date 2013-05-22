package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.pages.writeOff.WriteOffPage;

public class WriteOffSteps extends ScenarioSteps {

    WriteOffPage writeOffPage;
    CommonPage commonPage;

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
    public void executeWriteOff() {
        writeOffPage.executeWriteOff();
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

    public void itemCheck(String value) {
        writeOffPage.itemCheck(value);
    }

    public void itemCheckIsNotPresent(String value) {
        writeOffPage.itemCheckIsNotPresent(value);
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        writeOffPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, ExamplesTable checkValuesTable) {
        writeOffPage.checkListItemHasExpectedValueByFindByLocator(value, checkValuesTable);
    }

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
}
