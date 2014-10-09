package project.lighthouse.autotests.steps.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.supplier.SupplierListPage;
import project.lighthouse.autotests.pages.supplier.modal.SupplierCreateModalPage;
import project.lighthouse.autotests.pages.supplier.modal.SupplierEditModalPage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class SupplierSteps extends ScenarioSteps {

    SupplierListPage supplierListPage;
    SupplierCreateModalPage supplierCreateModalPage;
    SupplierEditModalPage supplierEditModalPage;

    private ExamplesTable examplesTable;
    private String name;

    @Step
    public void supplierListPageOpen() {
        supplierListPage.open();
    }

    @Step
    public void addNewSupplierButtonClick() {
        supplierListPage.addObjectButtonClick();
    }

    @Step
    public void supplierCreateModalPageInput(ExamplesTable examplesTable) {
        supplierCreateModalPage.input(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void supplierEditModalPageInput(ExamplesTable examplesTable) {
        supplierEditModalPage.input(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void supplierEditModalPageCheckValues() {
        supplierEditModalPage.checkValues(examplesTable);
    }

    @Step
    public void supplierCreateModalPageConfirmButtonClick() {
        supplierCreateModalPage.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void supplierEditModalPageConfirmButtonClick() {
        supplierEditModalPage.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void supplierCreateModalPageCloseIconClick() {
        supplierCreateModalPage.closeIconClick();
    }

    @Step
    public void supplierEditModalPageCloseIconClick() {
        supplierEditModalPage.closeIconClick();
    }

    @Step
    public void supplierCollectionCompareWithExampleTable(ExamplesTable examplesTable) {
        supplierListPage.compareWithExampleTable(examplesTable);
    }

    @Step
    public void clickOnTheSupplierWithName(String name) {
        supplierListPage.clickOnCollectionObjectByLocator(name);
    }

    @Step
    public void supplierCollectionNoContainSupplierWithName(String name) {
        supplierListPage.collectionNotContainObjectWithLocator(name);
    }

    @Step
    public void supplierCollectionContainSupplierWithName() {
        supplierListPage.collectionContainObjectWithLocator(name);
    }

    @Step
    public void supplierCollectionContainSupplierWithName(String name) {
        supplierListPage.collectionContainObjectWithLocator(name);
    }

    @Step
    public void assertSupplierCreateSupplierModalWindowTitle(String title) {
        assertThat(supplierCreateModalPage.getTitle(), is(title));
    }

    @Step
    public void assertSupplierEditSupplierModalWindowTitle(String title) {
        assertThat(supplierEditModalPage.getTitle(), is(title));
    }

    @Step
    public void assertSupplierListPageTitle(String title) {
        assertThat(supplierListPage.getTitle(), is(title));
    }

    @Step
    public void supplierCreateModalPageCheckErrorMessage(String elementName, String errorMessage) {
        supplierCreateModalPage.checkItemErrorMessage(elementName, errorMessage);
    }

    @Step
    public void supplierEditModalPageCheckErrorMessage(String elementName, String errorMessage) {
        supplierEditModalPage.checkItemErrorMessage(elementName, errorMessage);
    }

    @Step
    public void supplierCreateModalPageInputGeneratedText(String elementName, int count) {
        String generatedString = new StringGenerator(count).generateString("a");
        supplierCreateModalPage.input(elementName, generatedString);
        this.name = generatedString;
    }

    @Step
    public void supplierEditModalPageInputGeneratedText(String elementName, int count) {
        String generatedString = new StringGenerator(count).generateString("b");
        supplierEditModalPage.input(elementName, generatedString);
        this.name = generatedString;
    }
}
