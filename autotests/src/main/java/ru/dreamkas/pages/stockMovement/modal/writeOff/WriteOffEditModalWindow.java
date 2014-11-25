package ru.dreamkas.pages.stockMovement.modal.writeOff;

import org.openqa.selenium.WebDriver;

public class WriteOffEditModalWindow extends WriteOffCreateModalWindow {

    public WriteOffEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();

        putDefaultConfirmationOkButton(
                confirmationOkClick("Сохранить"));
    }
}
