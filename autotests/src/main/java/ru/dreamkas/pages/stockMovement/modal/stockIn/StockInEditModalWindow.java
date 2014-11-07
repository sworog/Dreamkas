package ru.dreamkas.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.WebDriver;

public class StockInEditModalWindow extends StockInCreateModalWindow {

    public StockInEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Сохранить");
    }
}
