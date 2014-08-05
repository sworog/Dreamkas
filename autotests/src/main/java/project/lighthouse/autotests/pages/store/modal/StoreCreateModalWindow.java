package project.lighthouse.autotests.pages.store.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

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
        return "//*[@id='modal-storeAdd']";
    }

    @Override
    public void closeIconClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'close')]")).click();
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }
}
