package project.lighthouse.autotests.steps.product;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import project.lighthouse.autotests.common.item.interfaces.Findable;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.catalog.group.GroupPage;
import project.lighthouse.autotests.pages.catalog.group.modal.ProductCreateModalWindow;
import project.lighthouse.autotests.pages.catalog.group.modal.ProductEditModalWindow;
import project.lighthouse.autotests.storage.Storage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;
import static org.junit.Assert.fail;

public class ProductSteps extends ScenarioSteps {

    GroupPage groupPage;
    ProductCreateModalWindow createNewProductModalWindow;
    ProductEditModalWindow editProductModalWindow;

    private ExamplesTable examplesTable;
    private String name;

    private static final String NO_PRODUCTS_MESSAGE = "В этой группе пока нет ни одного товара.";

    @Step
    public void createNewProductButtonClick() {
        groupPage.addObjectButtonClick();
    }

    @Step
    public void createNewProductModalWindowInput(ExamplesTable examplesTable) {
        createNewProductModalWindow.input(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void editNewProductModalWindowInput(ExamplesTable examplesTable) {
        editProductModalWindow.input(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void createNewProductModalWindowConfirmOkClick() {
        createNewProductModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void editProductModalWindowConfirmOkClick() {
        editProductModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertCreateNewProductModalWindowMarkUpValue(String value) {
        createNewProductModalWindow.checkValue("markUpValue", value);
    }

    @Step
    public void assertEditProductModalWindowMarkUpValue(String value) {
        editProductModalWindow.checkValue("markUpValue", value);
    }

    @Step
    public void assertCreateNewProductModalWindowMarkUpIsNotVisible() {
        By markUpValueFindBy = ((Findable) createNewProductModalWindow.getItems().get("markUpValue")).getFindBy();
        if (!createNewProductModalWindow.invisibilityOfElementLocated(markUpValueFindBy)) {
            fail("The markUp value is visible in create new product modal window");
        }
    }

    @Step
    public void assertEditProductModalWindowMarkUpIsNotVisible() {
        By markUpValueFindBy = ((Findable) editProductModalWindow.getItems().get("markUpValue")).getFindBy();
        if (!editProductModalWindow.invisibilityOfElementLocated(markUpValueFindBy)) {
            fail("The markUp value is visible in create new product modal window");
        }
    }

    @Step
    public void productCollectionCompareWithExampleTable(ExamplesTable examplesTable) {
        groupPage.compareWithExampleTable(examplesTable);
    }

    @Step
    public void productCollectionExactCompareWithExampleTable(ExamplesTable examplesTable) {
        groupPage.exactCompareExampleTable(examplesTable);
    }

    @Step
    public void productCollectionProductWithNameClick(String name) {
        groupPage.clickOnCollectionObjectByLocator(name);
    }

    @Step
    public void productCollectionProductWithNameClickOnProductWithStoredName() {
        groupPage.clickOnCollectionObjectByLocator(Storage.getCustomVariableStorage().getName());
    }

    @Step
    public void editProductModalWindowCheckStoredValues() {
        editProductModalWindow.checkValues(examplesTable);
    }

    @Step
    public void editProductModalWindowCheckValues(ExamplesTable examplesTable) {
        editProductModalWindow.checkValues(examplesTable);
    }

    @Step
    public void assertCreateNewProductModalWindowGroupFieldValue(String value) {
        createNewProductModalWindow.checkValue("group", value);
    }

    @Step
    public void createNewProductModalWindowCloseIconClick() {
        createNewProductModalWindow.closeIconClick();
    }

    @Step
    public void editProductModalWindowCloseIconClick() {
        editProductModalWindow.closeIconClick();
    }

    @Step
    public void productCollectionNotContainProductWithName(String name) {
        groupPage.collectionNotContainObjectWithLocator(name);
    }

    @Step
    public void productCollectionContainProductWithName(String name) {
        groupPage.collectionContainObjectWithLocator(name);
    }

    @Step
    public void deleteButtonClick() {
        editProductModalWindow.deleteButtonClick();
    }

    @Step
    public void confirmDeleteButtonClick() {
        editProductModalWindow.confirmDeleteButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertCreateNewProductModalWindowTitle(String title) {
        assertThat(createNewProductModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertEditProductModalWindowTitle(String title) {
        assertThat(editProductModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertCreateNewProductModalWindowFieldErrorMessage(String elementName, String errorMessage) {
        createNewProductModalWindow.checkItemErrorMessage(elementName, errorMessage);
    }

    @Step
    public void assertEditProductModalWindowFieldErrorMessage(String elementName, String errorMessage) {
        editProductModalWindow.checkItemErrorMessage(elementName, errorMessage);
    }

    @Step
    public void assertDeleteButtonLabel(String label) {
        assertThat(editProductModalWindow.getDeleteButtonText(), is(label));
    }

    @Step
    public void createNewProductModalWindowFieldGenerateText(String elementName, int number) {
        String generatedString = new StringGenerator(number).generateTestData();
        createNewProductModalWindow.input(elementName, generatedString);
        this.name = generatedString;
    }

    @Step
    public void productCollectionContainProductWithStoredName() {
        productCollectionContainProductWithName(name);
    }

    @Step
    public void editProductModalWindowFieldGenerateText(String elementName, int number) {
        String generatedString = new StringGenerator(number).generateString("b");
        editProductModalWindow.input(elementName, generatedString);
        this.name = generatedString;
    }

    @Step
    public void createNewProductModalWindowInput(String elementName, String value) {
        createNewProductModalWindow.input(elementName, value);
    }

    @Step
    public void editProductModalWindowInput(String elementName, String value) {
        editProductModalWindow.input(elementName, value);
    }

    @Step
    public void sortByNameClick() {
        groupPage.clickOnCommonItemWihName("sortByName");
    }

    @Step
    public void sortBySellingPriceClick() {
        groupPage.clickOnCommonItemWihName("sortBySellingPrice");
    }

    @Step
    public void sortByBarcodeClick() {
        groupPage.clickOnCommonItemWihName("sortByBarcode");
    }
}
