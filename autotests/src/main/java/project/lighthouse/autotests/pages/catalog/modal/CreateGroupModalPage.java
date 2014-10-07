package project.lighthouse.autotests.pages.catalog.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Create group modal page object
 */
public class CreateGroupModalPage extends ModalWindowPage {

    public CreateGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Input(this, "//*[@name='name']"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_group']";
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }
}
