package ru.dreamkas.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.pages.store.modal.StoreCreateModalWindow;

public class StockInStoreCreateModalWindow extends StoreCreateModalWindow {

    public StockInStoreCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@id, 'modal_store') and contains(@class, 'modal_visible')]";
    }
}
