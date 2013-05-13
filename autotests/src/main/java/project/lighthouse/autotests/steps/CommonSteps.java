package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.StaticDataCollections;
import project.lighthouse.autotests.pages.common.CommonPage;
import project.lighthouse.autotests.pages.invoice.InvoiceCreatePage;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

public class CommonSteps extends ScenarioSteps {

    CommonPage commonPage;
    InvoiceCreatePage invoiceCreatePage;
    ProductListPage productListPage;
    InvoiceListPage invoiceListPage;

    public CommonSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void checkTheRequiredPageIsOpen(String pageObjectName) {
        commonPage.isRequiredPageOpen(pageObjectName);
    }

    @Step
    public void checkErrorMessages(ExamplesTable errorMessageTable) {
        commonPage.checkErrorMessages(errorMessageTable);
    }

    @Step
    public void checkNoErrorMessages() {
        commonPage.checkNoErrorMessages();
    }

    @Step
    public void checkNoErrorMessages(ExamplesTable errorMessageTable) {
        commonPage.checkNoErrorMessages(errorMessageTable);
    }

    @Step
    public void checkAutoCompleteNoResults() {
        commonPage.checkAutoCompleteNoResults();
    }

    @Step
    public void checkAutoCompleteResults(ExamplesTable checkValuesTable) {
        commonPage.checkAutoCompleteResults(checkValuesTable);
    }

    @Step
    public void createProductPostRequestSend(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException {
        productListPage.open();
        commonPage.сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }

    @Step
    public void createInvoiceThroughPost(String invoiceName) throws JSONException {
        invoiceListPage.open();
        commonPage.createInvoiceThroughPost(invoiceName);
    }

    @Step
    public void createInvoiceThroughPostWithData(String invoiceName, String productName) throws JSONException {
        if (!StaticDataCollections.invoices.containsKey(invoiceName)) {
            createInvoiceThroughPost(invoiceName);
            continueCreatingInvoiceProduct(productName);
            StaticDataCollections.invoices.put(invoiceName, null);
        }
    }

    public void checkAlertText(String expectedText) {
        commonPage.checkAlertText(expectedText);
    }

    public void NoAlertIsPresent() {
        commonPage.NoAlertIsPresent();
    }

    @Step
    public void continueCreatingInvoiceProduct(String productName) {
        invoiceCreatePage.input("productName", productName);
        invoiceCreatePage.input("productAmount", "1");
        invoiceCreatePage.input("invoiceCost", "1");
        invoiceCreatePage.invoiceCreateButtonClick();
    }

    @Step
    public void averagePriceCalculation() {
        productListPage.open();
        commonPage.averagePriceCalculation();
    }
}
