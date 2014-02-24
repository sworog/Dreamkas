package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.commercialManager.catalog.CategoryPage;
import project.lighthouse.autotests.pages.commercialManager.catalog.GroupPage;
import project.lighthouse.autotests.pages.commercialManager.catalog.MarkUpTab;
import project.lighthouse.autotests.pages.commercialManager.catalog.SubCategoryPage;

public class CatalogSteps extends ScenarioSteps {

    GroupPage groupPage;
    CategoryPage categoryPage;
    SubCategoryPage subCategoryPage;
    MarkUpTab markUpTab;

    @Step
    public void openPage() {
        groupPage.open();
    }

    @Step
    public void startEditionButtonLinkClick() {
        groupPage.startEditionButtonLinkClick();
    }

    @Step
    public void startEditButtonLinkClickIsNotPresent() {
        groupPage.startEditButtonLinkClickIsNotPresent();
    }

    @Step
    public void stopEditionButtonLinkClick() {
        groupPage.stopEditionButtonLinkClick();
    }

    @Step
    public void groupCreate(String groupName) {
        groupPage.create(groupName);
    }

    @Step
    public void categoryCreate(String categoryName) {
        categoryPage.create(categoryName);
    }

    @Step
    public void subCategoryCreate(String subCategoryName) {
        subCategoryPage.create(subCategoryName);
    }

    @Step
    public void groupCheck(String name) {
        groupPage.check(name);
    }

    @Step
    public void categoryCheck(String name) {
        categoryPage.check(name);
    }

    @Step
    public void subCategoryCheck(String name) {
        subCategoryPage.check(name);
    }

    @Step
    public void popUpMenuInteraction(String name) {
        groupPage.popUpMenuInteraction(name);
    }

    @Step
    public void popUpCategoryMenuInteraction(String name) {
        categoryPage.popUpMenuInteraction(name);
    }

    @Step
    public void popUpSubCategoryMenuInteraction(String name) {
        subCategoryPage.popUpMenuInteraction(name);
    }

    @Step
    public void itemDeleteThroughPopUpMenu() {
        groupPage.popUpMenuDelete();
    }

    @Step
    public void popUpMenuAccept() {
        groupPage.popUpMenuAccept();
    }

    @Step
    public void popUpMenuCancel() {
        groupPage.popUpMenuCancel();
    }

    @Step
    public void groupCheckIsNotPresent(String groupName) {
        groupPage.checkIsNotPresent(groupName);
    }

    @Step
    public void categoryCheckIsNotPresent(String categoryName) {
        categoryPage.checkIsNotPresent(categoryName);
    }

    @Step
    public void subCategoryIsNotPresent(String subCategoryName) {
        subCategoryPage.checkIsNotPresent(subCategoryName);
    }

    @Step
    public void groupClick(String groupName) {
        groupPage.itemClick(groupName);
    }

    @Step
    public void categoryClick(String categoryName) {
        categoryPage.itemClick(categoryName);
    }

    @Step
    public void subCategoryClick(String subCategoryName) {
        subCategoryPage.itemClick(subCategoryName);
    }

    @Step
    public void itemEditThroughPopUpMenu() {
        groupPage.popUpMenuEdit();
    }

    @Step
    public void popUpMenuProductCreate() {
        groupPage.popUpMenuProductCreate();
    }

    @Step
    public void checkItemParent(String item, String parent) {
        groupPage.checkItemParent(item, parent);
    }

    @Step
    public void input(String elementName, String data) {
        groupPage.input(elementName, data);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = new StringGenerator(charNumber).generateTestData();
        input(elementName, generatedData);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber, String str) {
        String generatedData = new StringGenerator(charNumber).generateTestData(str);
        input(elementName, generatedData);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        groupPage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void addNewGroupButtonClick() {
        groupPage.addNewButtonClick();
    }

    @Step
    public void addNewCategoryClick() {
        categoryPage.addNewButtonClick();
    }

    @Step
    public void addNewSubCategoryClick() {
        subCategoryPage.addNewButtonClick();
    }

    @Step
    public void addNewButtonConfirmClick() {
        groupPage.addNewButtonConfirmClick();
    }

    @Step
    public void addNewSubCategoryConfirmClick() {
        subCategoryPage.addNewButtonConfirmClick();
    }

    @Step
    public void mainTabClick(String mainTabType) {
        groupPage.mainTab(mainTabType).click();
    }

    @Step
    public void propertiesTabClick(String propertiesTabType) {
        groupPage.propertiesTab(propertiesTabType).click();
    }

    @Step
    public void saveMarkUpButtonClick() {
        markUpTab.saveMarkUpButtonClick();
    }

    @Step
    public void retailMarkupMinSet(String value) {
        markUpTab.input("retailMarkupMin", value);
    }

    @Step
    public void retailMarkupMaxSet(String value) {
        markUpTab.input("retailMarkupMax", value);
    }

    @Step
    public void retailMarkupMinCheck(String value) {
        markUpTab.check("retailMarkupMin", value);
    }

    @Step
    public void retailMarkupMaxCheck(String value) {
        markUpTab.check("retailMarkupMax", value);
    }

    @Step
    public void checkSuccessMessage(String expectedMessage) {
        markUpTab.checkSuccessMessage(expectedMessage);
    }

    @Step
    public void setRoundings(String value) {
        markUpTab.input("rounding", value);
    }

    @Step
    public void checkDropDownDefaultValue(String expectedValue) {
        markUpTab.checkDropDownDefaultValue(expectedValue);
    }

    @Step
    public void productsExportLinkClick() {
        groupPage.productsExportLinkClick();
    }

    @Step
    public void productsExportLinkIsNotPresent() {
        groupPage.productsExportLinkIsNotPresent();
    }
}
