package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.pages.catalog.modal.CreateGroupModalPage;

public class InvoiceCreateGroupModalPage extends CreateGroupModalPage {


    public InvoiceCreateGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@id, 'modal_group')]";
    }
}
