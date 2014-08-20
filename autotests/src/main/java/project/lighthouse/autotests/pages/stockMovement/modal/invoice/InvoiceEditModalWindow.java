package project.lighthouse.autotests.pages.stockMovement.modal.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

public class InvoiceEditModalWindow extends InvoiceCreateModalWindow {

    public InvoiceEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_invoiceEdit']";
    }

    public void deleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink']")).click();
    }

    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink invoice__removeLink']")).click();
    }
}
