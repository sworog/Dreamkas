package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.common.CommonPage;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

public class CommonSteps extends ScenarioSteps{

    CommonPage commonPage;
    ProductListPage productListPage;
    InvoiceListPage invoiceListPage;

    public CommonSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void checkTheRequiredPageIsOpen(String pageObjectName){
        commonPage.isRequiredPageOpen(pageObjectName);
    }

    @Step
    public void checkErrorMessages(ExamplesTable errorMessageTable){
        commonPage.checkErrorMessages(errorMessageTable);
    }

    @Step
    public void checkNoErrorMessages(){
        commonPage.checkNoErrorMessages();
    }

    @Step
    public void checkNoErrorMessages(ExamplesTable errorMessageTable){
        commonPage.checkNoErrorMessages(errorMessageTable);
    }

    @Step
    public void checkAutoCompleteNoResults(){
        commonPage.checkAutoCompleteNoResults();
    }

    @Step
    public void checkAutoCompleteResults(ExamplesTable checkValuesTable){
        commonPage.checkAutoCompleteResults(checkValuesTable);
    }

    @Step
    public void createProductPostRequestSend(String name, String sku, String barcode, String units){
        productListPage.open();
        commonPage.сreateProductThroughPost(name, sku, barcode, units);
    }

    @Step
    public void createInvoiceThroughPost(String invoiceName){
        invoiceListPage.open();
        commonPage.createInvoiceThroughPost(invoiceName);
    }
}
