package ru.dreamkas.pages.supplier.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

public class SupplierEditModalPage extends SupplierCreateModalPage {

    public SupplierEditModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }
}
