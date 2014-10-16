package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.pages.supplier.modal.SupplierCreateModalPage;

public class InvoiceSupplierCreateModalWindow extends SupplierCreateModalPage {

    public InvoiceSupplierCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@class='modal-dialog modal__dialog_supplier']";
    }
}
