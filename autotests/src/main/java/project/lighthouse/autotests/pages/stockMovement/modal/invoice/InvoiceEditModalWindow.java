package project.lighthouse.autotests.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;

public class InvoiceEditModalWindow extends InvoiceCreateModalWindow {

    public InvoiceEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_invoiceEdit']";
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Сохранить");
    }

    @Override
    public void confirmDeleteButtonClick() {
        confirmDeleteButtonClick("invoice__removeLink");
    }
}
