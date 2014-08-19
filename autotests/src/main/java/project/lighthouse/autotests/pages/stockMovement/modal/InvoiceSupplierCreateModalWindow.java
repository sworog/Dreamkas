package project.lighthouse.autotests.pages.stockMovement.modal;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.supplier.modal.SupplierCreateModalPage;

public class InvoiceSupplierCreateModalWindow extends SupplierCreateModalPage {

    public InvoiceSupplierCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@class='modal-dialog modal__dialog_supplier']";
    }
}
