package ru.dreamkas.pages.modal;

import net.thucydides.core.annotations.WhenPageOpens;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.item.interfaces.Clickable;
import ru.dreamkas.common.item.interfaces.CommonItemType;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.common.pageObjects.ModalWindowPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Common page object representing modal window
 */
public abstract class ModalWindowPage extends CommonPageObject implements ModalWindowPageObject {

    public ModalWindowPage(WebDriver driver) {
        super(driver);
        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Сохранить"));
    }

    public abstract String modalWindowXpath();

    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal-title']")).getText();
    }

    public void putDefaultConfirmationOkButton(CommonItemType commonItemType) {
        put("defaultConfirmationOkButton", commonItemType);
    }

    public void confirmationOkClick() {
        ((Clickable)getItems().get("defaultConfirmationOkButton")).click();
    }

    @WhenPageOpens
    public void whenPageOpens() {
        //Check that modal window is open
        findVisibleElement(By.id("modal-group"));
    }

    public void closeIconClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'modal__closeLink')]")).click();
    }

    @Override
    public void deleteButtonClick() {
        throw new AssertionError("This modal window does not have delete button");
    }

    @Override
    public void confirmDeleteButtonClick() {
        throw new AssertionError("This modal window does not have delete button");
    }

    public void clickInTheModalWindowByXpath(String xpath) {
        findVisibleElement(By.xpath(modalWindowXpath() + xpath)).click();
    }

    public void clickInTheModalWindowByFinBy(By findBy) {
        findVisibleElement(By.xpath(modalWindowXpath())).findElement(findBy).click();
    }

    @Override
    public void continueButtonClick() {
        clickInTheModalWindowByXpath("//*[@name='removeContinue']");
    }

    @Override
    public void close() {
        closeIconClick();
    }
}
