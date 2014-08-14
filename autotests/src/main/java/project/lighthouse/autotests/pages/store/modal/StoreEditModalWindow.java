package project.lighthouse.autotests.pages.store.modal;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

public class StoreEditModalWindow extends StoreCreateModalWindow {

    public StoreEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal-storeEdit']";
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }
}
