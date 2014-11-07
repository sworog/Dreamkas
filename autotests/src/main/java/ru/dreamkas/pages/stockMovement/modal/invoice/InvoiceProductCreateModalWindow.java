package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.pages.catalog.group.modal.ProductCreateModalWindow;

public class InvoiceProductCreateModalWindow extends ProductCreateModalWindow {

    public InvoiceProductCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@id, 'modal_product')]";
    }
}
