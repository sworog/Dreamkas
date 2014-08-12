package project.lighthouse.autotests.steps.stockMovement;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.objects.web.invoiceProduct.InvoiceProductCollection;
import project.lighthouse.autotests.objects.web.invoiceProduct.InvoiceProductObject;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementObjectCollection;
import project.lighthouse.autotests.pages.stockMovement.StockMovementPage;
import project.lighthouse.autotests.pages.stockMovement.modal.InvoiceCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.InvoiceEditModalWindow;
import project.lighthouse.autotests.storage.Storage;

import java.util.List;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StockMovementSteps extends ScenarioSteps {

    StockMovementPage stockMovementPage;
    InvoiceCreateModalWindow invoiceCreateModalWindow;
    InvoiceEditModalWindow invoiceEditModalWindow;

    @Step
    public void stockMovementPageOpen() {
        stockMovementPage.open();
    }

    @Step
    public void acceptProductsButtonClick() {
        stockMovementPage.addObjectButtonClick();
    }

    @Step
    public void invoiceCreateModalWindowInput(ExamplesTable examplesTable) {
        invoiceCreateModalWindow.fieldInput(examplesTable);
    }

    @Step
    public void invoiceEditModalWindowWindowInput(ExamplesTable examplesTable) {
        invoiceEditModalWindow.fieldInput(examplesTable);
    }

    @Step
    public void paidCheckBoxClick() {
        invoiceCreateModalWindow.paidCheckBoxClick();
    }

    @Step
    public void invoiceEditModalWindowPaidCheckBoxClick() {
        invoiceEditModalWindow.paidCheckBoxClick();
    }

    @Step
    public void addProductToInvoiceButtonClick() {
        invoiceCreateModalWindow.addProductToInvoiceButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void invoiceEditModalWindowAddProductToInvoiceButtonClick() {
        invoiceEditModalWindow.addProductToInvoiceButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void acceptInvoiceButtonClick() {
        invoiceCreateModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void saveInvoiceButtonClick() {
        invoiceEditModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void stockMovementPageContainInvoice(ExamplesTable examplesTable) {
        StockMovementObjectCollection stockMovementObjectCollection = getStockMovementObjectCollection();
        if (stockMovementObjectCollection != null) {
            stockMovementObjectCollection.compareWithExampleTable(examplesTable);
        }
    }

    private StockMovementObjectCollection getStockMovementObjectCollection() {
        StockMovementObjectCollection stockMovementObjectCollection = null;
        try {
            stockMovementObjectCollection = stockMovementPage.getStockMovementObjectCollection();
        } catch (TimeoutException e) {
            stockMovementPage.containsText("Не найдено ни одной операции с товарами.");
        } catch (StaleElementReferenceException e) {
            stockMovementObjectCollection = stockMovementPage.getStockMovementObjectCollection();
        }
        return stockMovementObjectCollection;
    }

    private InvoiceProductCollection getInvoiceProductCollection() {
        InvoiceProductCollection invoiceProductCollection;
        try {
            invoiceProductCollection = invoiceCreateModalWindow.getInvoiceProductCollection();
        } catch (StaleElementReferenceException e) {
            invoiceProductCollection = invoiceCreateModalWindow.getInvoiceProductCollection();
        }
        return invoiceProductCollection;
    }

    @Step
    public void invoiceProductCollectionExactCompare(ExamplesTable examplesTable) {
        getInvoiceProductCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void invoiceProductWithNameDeleteIconClick(String name) {
        InvoiceProductObject invoiceProductObject =
                (InvoiceProductObject) getInvoiceProductCollection().getAbstractObjectByLocator(name);
        invoiceProductObject.deleteIconClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertStockMovementPageTitle(String title) {
        assertThat(stockMovementPage.getTitle(), is(title));
    }

    @Step
    public void assertInvoiceCreateModalWindowPageTitle(String title) {
        assertThat(invoiceCreateModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertInvoiceEditModalWindowPageTitle(String title) {
        assertThat(invoiceEditModalWindow.getTitle(), is(title));
    }

    @Step
    public void openLastCreatedInvoiceInStockMovementPage() throws JSONException {
        StockMovementObjectCollection stockMovementObjectCollection = stockMovementPage.getStockMovementObjectCollection();
        if (stockMovementObjectCollection != null) {
            stockMovementObjectCollection.clickByLocator(getLastCreatedInvoice().getNumber());
        }
    }

    @Step
    public void stockMovementCollectionDontContainLastCreatedInvoice() throws JSONException {
        StockMovementObjectCollection stockMovementObjectCollection = getStockMovementObjectCollection();
        if (stockMovementObjectCollection != null) {
            stockMovementObjectCollection.notContains(getLastCreatedInvoice().getNumber());
        }
    }

    private Invoice getLastCreatedInvoice() {
        List<Invoice> invoiceList = Storage.getInvoiceVariableStorage().getInvoiceList();
        return invoiceList.get(invoiceList.size() - 1);
    }

    @Step
    public void deleteInvoiceLinkClick() {
        invoiceEditModalWindow.deleteButtonClick();
    }

    @Step
    public void confirmDeleteInvoiceLinkClick() {
        invoiceEditModalWindow.confirmDeleteButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertInvoiceCreateModalWindowTotalSum(String totalSum) {
        assertThat(invoiceCreateModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertInvoiceEditModalWindowTotalSum(String totalSum) {
        assertThat(invoiceEditModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertInvoiceDateIsNowDate() {
        invoiceCreateModalWindowCheckValue("date", DateTimeHelper.getDate("todayDate"));
    }

    @Step
    public void invoiceEditModalWindowCheckValue(String elementName, String value) {
        invoiceEditModalWindow.checkValue(elementName, value);
    }

    @Step
    public void invoiceCreateModalWindowCheckValue(String elementName, String value) {
        invoiceCreateModalWindow.checkValue(elementName, value);
    }
}
