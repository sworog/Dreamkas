package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.common.ICommonPage;
import project.lighthouse.autotests.pages.invoice.InvoiceBrowsing;
import project.lighthouse.autotests.pages.invoice.InvoiceCreatePage;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;

public class InvoiceSteps extends ScenarioSteps{

    InvoiceCreatePage invoiceCreatePage;
    InvoiceListPage invoiceListPage;
    InvoiceBrowsing invoiceBrowsing;
    ICommonPage commonPage;

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

    @Step
    public void CheckCardValue(String elementName, String expectedValue){
        invoiceBrowsing.CheckCardValue(elementName, expectedValue);
    }

    @Step
    public void CheckCardValue(ExamplesTable checkValuesTable){
        invoiceBrowsing.CheckCardValue(checkValuesTable);
    }

    @Step
    public void EditButtonClick(){
        invoiceBrowsing.EditButtonClick();
    }

    @Step
    public void ListItemClick(String skuValue){
        invoiceListPage.ListItemClick(skuValue);
    }

    @Step
    public void GenerateTestCharData(String elementName, int charNumber){
        String generatedData = commonPage.GenerateTestData(charNumber);
        Input(elementName, generatedData);
    }
    @Step
    public void CheckFieldLength(String elementName, int fieldLength){
        invoiceCreatePage.CheckFieldLength(elementName, fieldLength);
    }

    @Step
    public void CheckTheDateisNowDate(String elementName){
        String NowDate = ICommonPage.GetTodayDate();
        CheckCardValue(elementName, NowDate);
    }
}
