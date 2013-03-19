package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.invoice.InvoiceCreatePage;

public class InvoiceSteps extends ScenarioSteps{

    InvoiceCreatePage invoiceCreatePage;

    public InvoiceSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void OpenInvoiceCreatePage(){
        invoiceCreatePage.open();
    }

    @Step
    public void Input(String elementName, String inputText){
        invoiceCreatePage.Input(elementName, inputText);
    }

    @Step
    public void InvoiceCreateButtonClick(){
        invoiceCreatePage.InvoiceCreateButtonClick();
    }
}
