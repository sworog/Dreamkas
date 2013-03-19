package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.invoice.InvoiceCreatePage;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;

public class InvoiceSteps extends ScenarioSteps{

    InvoiceCreatePage invoiceCreatePage;
    InvoiceListPage invoiceListPage;

    public InvoiceSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void OpenInvoiceCreatePage(){
        invoiceCreatePage.open();
    }

    @Step
    public void OpenInvoiceListPage(){
        invoiceListPage.open();
    }

    @Step
    public void InvoiceListItemCreate(){
        invoiceListPage.InvoiceListItemCreate();
    }

    @Step
    public void Input(String elementName, String inputText){
        invoiceCreatePage.Input(elementName, inputText);
    }

    @Step
    public void InvoiceCreateButtonClick(){
        invoiceCreatePage.InvoiceCreateButtonClick();
    }

    @Step
    public void InvoiceCloseButtonClick(){
        invoiceCreatePage.InvoiceCloseButtonClick();
    }

    @Step
    public void ListItemCheck(String skuValue){
        invoiceListPage.ListItemCheck(skuValue);
    }

    @Step
    public void CheckInvoiceListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        invoiceListPage.CheckInvoiceListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }


}
