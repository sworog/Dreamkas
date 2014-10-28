package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.items.NonType;

public class InvoiceEditModalWindow extends InvoiceCreateModalWindow {

    public InvoiceEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();

        put("заголовок успешного удаления", new NonType(this, "//*[@name='successRemoveTitle']"));
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Сохранить");
    }
}
