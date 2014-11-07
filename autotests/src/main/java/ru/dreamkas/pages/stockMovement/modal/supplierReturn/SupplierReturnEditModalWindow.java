package ru.dreamkas.pages.stockMovement.modal.supplierReturn;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.items.NonType;

public class SupplierReturnEditModalWindow extends SupplierReturnCreateModalWindow {
    public SupplierReturnEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Сохранить");
    }
}
