package ru.dreamkas.pages.stockMovement.modal.supplierReturn;

import org.openqa.selenium.WebDriver;

public class SupplierReturnEditModalWindow extends SupplierReturnCreateModalWindow {
    public SupplierReturnEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Сохранить");
    }
}
