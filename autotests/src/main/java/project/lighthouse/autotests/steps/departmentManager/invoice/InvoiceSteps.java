package project.lighthouse.autotests.steps.departmentManager.invoice;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.elements.preLoader.ProductEditionPreLoader;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.web.invoice.InvoiceProductObject;
import project.lighthouse.autotests.objects.web.invoice.InvoiceProductsCollection;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoicePage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class InvoiceSteps extends ScenarioSteps {

    InvoicePage invoicePage;

    private ExamplesTable examplesTable;

    @Step
    public void assertFieldLabel(String elementName) {
        invoicePage.checkFieldLabel(elementName);
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        invoicePage.fieldInput(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void checkValues() {
        invoicePage.checkValues(examplesTable);
    }

    @Step
    public InvoiceProductsCollection getInvoiceProductsCollection() {
        return invoicePage.getInvoiceProductsCollection();
    }

    @Step
    public InvoiceProductObject getInvoiceProductObject(String locator) {
        return (InvoiceProductObject) getInvoiceProductsCollection().getAbstractObjectByLocator(locator);
    }

    @Step
    public void invoiceProductObjectQuantityType(String locator, String value) {
        getInvoiceProductObject(locator).quantityType(value);
    }

    @Step
    public void invoiceProductObjectPriceType(String locator, String value) {
        getInvoiceProductObject(locator).priceType(value);
    }

    @Step
    public void invoiceProductEditionPreLoaderWait() {
        new ProductEditionPreLoader(getDriver()).await();
    }

    @Step
    public void invoiceProductsCollectionExactCompare(ExamplesTable examplesTable) {
        getInvoiceProductsCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void assertInvoiceTotalSum(String expected) {
        assertThat(expected, is(invoicePage.getTotalSum()));
    }

    @Step
    public void assertInvoiceVatSum(String expected) {
        assertThat(expected, is(invoicePage.getVatSum()));
    }

    @Step
    public void acceptProductsButtonClick() {
        invoicePage.acceptProductsButtonClick();
    }

    @Step
    public void openStoreInvoiceCreatePage(Store store) throws JSONException {
        String url = String.format(
                "%s/stores/%s/invoices/create",
                UrlHelper.getWebFrontUrl(),
                store.getId());
        getDriver().navigate().to(url);
    }
}
