package project.lighthouse.autotests.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.catalog.group.modal.CreateNewProductModalWindow;

public class InvoiceProductCreateModalWindow extends CreateNewProductModalWindow {

    public InvoiceProductCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@class='modal-dialog modal__dialog_product']";
    }
}
