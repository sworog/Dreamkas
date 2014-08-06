package project.lighthouse.autotests.steps.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
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
        supplierCreateModalPage.fieldInput(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void supplierEditModalPageInput(ExamplesTable examplesTable) {
        supplierEditModalPage.fieldInput(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void supplierEditModalPageCheckValues() {
        supplierEditModalPage.checkValues(examplesTable);
    }

    @Step
    public void supplierCreateModalPageConfirmButtonClick() {
        supplierCreateModalPage.confirmationOkClick();
    }

    @Step
    public void supplierEditModalPageConfirmButtonClick() {
        supplierEditModalPage.confirmationOkClick();
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
        getSupplierCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void clickOnTheSupplierWithName(String name) {
        getSupplierCollection().clickByLocator(name);
    }

    @Step
    public void supplierCollectionNoContainSupplierWithName(String name) {
        getSupplierCollection().notContains(name);
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
        assertThat(supplierCreateModalPage.getTitleText(), is(title));
    }

    @Step
    public void assertSupplierEditSupplierModalWindowTitle(String title) {
        assertThat(supplierEditModalPage.getTitleText(), is(title));
    }

    @Step
    public void assertSupplierListPageTitle(String title) {
        assertThat(supplierListPage.getTitle(), is(title));
    }
}
