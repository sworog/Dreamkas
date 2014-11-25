package ru.dreamkas.pages.store.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

public class StoreEditModalWindow extends StoreCreateModalWindow {

    public StoreEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Сохранить"));
    }
}
