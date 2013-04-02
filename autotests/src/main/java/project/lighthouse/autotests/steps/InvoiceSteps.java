package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.common.CommonPage;
import project.lighthouse.autotests.pages.invoice.InvoiceBrowsing;
import project.lighthouse.autotests.pages.invoice.InvoiceCreatePage;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;

public class InvoiceSteps extends ScenarioSteps{

    InvoiceCreatePage invoiceCreatePage;
    InvoiceListPage invoiceListPage;
    InvoiceBrowsing invoiceBrowsing;
    CommonPage commonPage;

    public InvoiceSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void openInvoiceCreatePage(){
        invoiceCreatePage.open();
    }

    @Step
    public void openInvoiceListPage(){
        invoiceListPage.open();
    }

    @Step
    public void invoiceListItemCreate(){
        invoiceListPage.invoiceListItemCreate();
    }

    @Step
    public void input(String elementName, String inputText){
        invoiceCreatePage.input(elementName, inputText);
    }

    @Step
    public void invoiceCreateButtonClick(){
        invoiceCreatePage.invoiceCreateButtonClick();
    }

    @Step
    public void invoiceCloseButtonClick(){
        invoiceCreatePage.invoiceCloseButtonClick();
    }

    @Step
    public void listItemCheck(String skuValue){
        invoiceListPage.listItemCheck(skuValue);
    }

    @Step
    public void checkInvoiceListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        invoiceListPage.checkInvoiceListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String elementName, String expectedValue){
        invoiceBrowsing.checkCardValue(elementName, expectedValue);
    }

    @Step
    public void checkCardValue(ExamplesTable checkValuesTable){
        invoiceBrowsing.checkCardValue(checkValuesTable);
    }

    @Step
    public void editButtonClick(){
        invoiceBrowsing.editButtonClick();
    }

    @Step
    public void listItemClick(String skuValue){
        invoiceListPage.listItemClick(skuValue);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber){
        String generatedData = commonPage.generateTestData(charNumber);
        input(elementName, generatedData);
    }
    @Step
    public void checkFieldLength(String elementName, int fieldLength){
        invoiceCreatePage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void checkTheDateisNowDate(String elementName){
        String NowDate = CommonPage.getTodayDate(CommonPage.DATE_TIME_PATTERN);
        invoiceBrowsing.shouldContainsText(elementName, NowDate);
    }
}
