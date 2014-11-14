package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.pages.store.modal.StoreCreateModalWindow;

public class InvoiceStoreCreateModalWindow extends StoreCreateModalWindow {

    public InvoiceStoreCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@id, 'modal_store')]";
    }
}
