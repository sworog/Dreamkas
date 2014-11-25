package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;

public class InvoiceEditModalWindow extends InvoiceCreateModalWindow {

    public InvoiceEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();

        putDefaultConfirmationOkButton(
                confirmationOkClick("Сохранить"));
    }
}
