package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.pages.catalog.CatalogPage;
import project.lighthouse.autotests.pages.catalog.ClassPage;

public class CatalogSteps extends ScenarioSteps {

    CatalogPage catalogPage;
    ClassPage classPage;
    CommonPage commonPage;

    public CatalogSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void openPage() {
        catalogPage.open();
    }

    @Step
    public void startEditionButtonLinkClick() {
        catalogPage.startEditionButtonLinkClick();
    }

    @Step
    public void stopEditionButtonLinkClick() {
        catalogPage.stopEditionButtonLinkClick();
    }

    @Step
    public void classCreate(String className) {
        catalogPage.create(className);
    }

    @Step
    public void groupCreate(String groupName) {
        classPage.create(groupName);
    }

    @Step
    public void classCheck(String name) {
        catalogPage.check(name);
    }

    @Step
    public void groupCheck(String name) {
        classPage.check(name);
    }

    @Step
    public void popUpMenuInteraction(String name) {
        catalogPage.popUpMenuInteraction(name);
    }

    @Step
    public void itemDeleteThroughPopUpMenu() {
        catalogPage.popUpMenuDelete();
    }

    @Step
    public void popUpMenuAccept() {
        catalogPage.popUpMenuAccept();
    }

    @Step
    public void popUpMenuCancel() {
        catalogPage.popUpMenuCancel();
    }

    @Step
    public void classCheckIsNotPresent(String className) {
        catalogPage.checkIsNotPresent(className);
    }

    @Step
    public void groupCheckIsNotPresent(String groupName) {
        classPage.checkIsNotPresent(groupName);
    }

    @Step
    public void classClick(String className) {
        catalogPage.itemClick(className);
    }

    @Step
    public void groupClick(String groupName) {
        classPage.itemClick(groupName);
    }

    @Step
    public void itemEditThroughPopUpMenu(String className, String newClassName) {
        catalogPage.popUpMenuEdit(className, newClassName);
    }

    @Step
    public void checkItemParent(String item, String parent) {
        catalogPage.checkItemParent(item, parent);
    }

    @Step
    public void input(String elementName, String data) {
        catalogPage.input(elementName, data);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = commonPage.generateTestData(charNumber);
        input(elementName, generatedData);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        catalogPage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void addNewButtonClick() {
        catalogPage.addNewButtonClick();
    }

    @Step
    public void addNewGroupClick() {
        classPage.addNewButtonClick();
    }

    @Step
    public void addNewButtonConfirmClick() {
        catalogPage.addNewButtonConfirmClick();
    }
}
