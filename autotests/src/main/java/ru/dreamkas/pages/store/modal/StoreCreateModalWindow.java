package ru.dreamkas.pages.store.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class StoreCreateModalWindow extends ModalWindowPage {

    public StoreCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Input(this, "//*[@name='name']"));
        put("address", new Input(this, "//*[@name='address']"));
    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@id, 'modal_store') and contains(@class, 'modal_visible')]";
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }
}
