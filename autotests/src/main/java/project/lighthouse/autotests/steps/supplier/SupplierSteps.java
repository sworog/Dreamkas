package project.lighthouse.autotests.steps.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.objects.web.supplier.SupplierCollection;
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
        SupplierCollection supplierCollection = getSupplierCollection();
        if (supplierCollection != null) {
            getSupplierCollection().compareWithExampleTable(examplesTable);
        }
    }

    @Step
    public void clickOnTheSupplierWithName(String name) {
        SupplierCollection supplierCollection = getSupplierCollection();
        if (supplierCollection != null) {
            getSupplierCollection().clickByLocator(name);
        }
    }

    @Step
    public void supplierCollectionNoContainSupplierWithName(String name) {
        SupplierCollection supplierCollection = getSupplierCollection();
        if (supplierCollection != null) {
            getSupplierCollection().notContains(name);
        }
    }

    @Step
    public void supplierCollectionContainSupplierWithName() {
        SupplierCollection supplierCollection = getSupplierCollection();
        if (supplierCollection != null) {
            getSupplierCollection().contains(name);
        }
    }

    @Step
    public void supplierCollectionContainSupplierWithName(String name) {
        SupplierCollection supplierCollection = getSupplierCollection();
        if (supplierCollection != null) {
            getSupplierCollection().contains(name);
        }
    }

    private SupplierCollection getSupplierCollection() {
        SupplierCollection supplierCollection = null;
        try {
            supplierCollection = supplierListPage.getSupplierCollection();
        } catch (TimeoutException e) {
            supplierListPage.containsText("У вас ещё нет ни одного поставщика");
        } catch (StaleElementReferenceException e) {
            supplierCollection = supplierListPage.getSupplierCollection();
        }
        return supplierCollection;
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
        supplierCreateModalPage.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void supplierEditModalPageCheckErrorMessage(String elementName, String errorMessage) {
        supplierEditModalPage.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
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
