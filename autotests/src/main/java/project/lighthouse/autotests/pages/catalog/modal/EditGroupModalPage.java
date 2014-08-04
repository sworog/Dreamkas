package project.lighthouse.autotests.pages.catalog.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Edit group modal page object
 */
public class EditGroupModalPage extends CreateGroupModalPage {

    public EditGroupModalPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal-groupEdit']";
    }

    public void deleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink group__removeLink']")).click();
    }

    public void deleteButtonConfirmClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']/*[@class='removeLink group__removeLink']")).click();
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }
}
