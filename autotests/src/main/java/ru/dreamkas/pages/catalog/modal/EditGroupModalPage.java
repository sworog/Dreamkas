package ru.dreamkas.pages.catalog.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Edit group modal page object
 */
public class EditGroupModalPage extends CreateGroupModalPage {

    public EditGroupModalPage(WebDriver driver) {
        super(driver);
    }

    public void deleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'removeLink')]")).click();
    }

    public void deleteButtonConfirmClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']/*[contains(@class, 'removeLink')]")).click();
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }
}
