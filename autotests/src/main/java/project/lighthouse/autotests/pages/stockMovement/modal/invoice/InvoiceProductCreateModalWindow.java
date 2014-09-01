package project.lighthouse.autotests.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.catalog.group.modal.ProductCreateModalWindow;

public class InvoiceProductCreateModalWindow extends ProductCreateModalWindow {

    public InvoiceProductCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@class='modal-dialog modal__dialog_product']";
    }
}
