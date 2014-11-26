package ru.dreamkas.pages.store.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;

public class StoreEditModalWindow extends StoreCreateModalWindow {

    public StoreEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Сохранить"));
        put("заголовок успешного удаления магазина", new NonType(this, "//*[@name='successRemoveTitle']"));
        put("название удаленного магазина", new NonType(this, "//*[@name='removedSupplierName']"));
    }
}
