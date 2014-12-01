package ru.dreamkas.pages.modal;

import net.thucydides.core.annotations.WhenPageOpens;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.item.interfaces.Clickable;
import ru.dreamkas.common.item.interfaces.CommonItemType;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.common.pageObjects.ModalWindowPageObject;
import ru.dreamkas.elements.items.NonType;

/**
 * Common page object representing modal window
 */
public abstract class ModalWindowPage extends CommonPageObject implements ModalWindowPageObject {

    protected static final String DEFAULT_DELETE_BUTTON = "кнопка удаления";
    private static final String DEFAULT_CONFIRM_DELETE_BUTTON = "кнопка подтверждения удаления";
    private static final String DEFAULT_CONFIRM_OK_BUTTON = "кнопка подтверждения операции";

    public ModalWindowPage(WebDriver driver) {
        super(driver);
        put("кнопка закрытия модального окна", new NonType(this, "//*[contains(@class, 'modal__closeLink')]"));
        put("кнопка продолжить", new NonType(this, "//*[@name='removeContinue']"));
        putDefaultDeleteButton(new NonType(this, "//*[contains(@class, 'removeButton')]/a"));
        putDefaultConfirmationDeleteButton(new NonType(this, "//*[contains(@class, 'removeButton_confirm')]/a[contains(@class, 'confirm')]"));
    }

    public abstract String modalWindowXpath();

    public String getTitle() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='modal__title']")).getText();
    }

    protected void putDefaultConfirmationOkButton(CommonItemType commonItemType) {
        put(DEFAULT_CONFIRM_OK_BUTTON, commonItemType);
    }

    protected void putDefaultDeleteButton(CommonItemType commonItemType) {
        put(DEFAULT_DELETE_BUTTON, commonItemType);
    }

    protected void putDefaultConfirmationDeleteButton(CommonItemType commonItemType) {
        put(DEFAULT_CONFIRM_DELETE_BUTTON, commonItemType);
    }

    public void confirmationOkClick() {
        ((Clickable)getItems().get(DEFAULT_CONFIRM_OK_BUTTON)).click();
    }

    @WhenPageOpens
    public void whenPageOpens() {
        //Check that modal window is open
        findVisibleElement(By.id("modal-group"));
    }

    @Override
    public void deleteButtonClick() {
        ((Clickable)getItems().get(DEFAULT_DELETE_BUTTON)).click();
    }

    @Override
    public void confirmDeleteButtonClick() {
        ((Clickable)getItems().get(DEFAULT_CONFIRM_DELETE_BUTTON)).click();
    }

    public void clickInTheModalWindowByXpath(String xpath) {
        findVisibleElement(By.xpath(modalWindowXpath() + xpath)).click();
    }

    public void clickInTheModalWindowByFinBy(By findBy) {
        findVisibleElement(By.xpath(modalWindowXpath())).findElement(findBy).click();
    }

    @Override
    public void continueButtonClick() {
        ((Clickable)getItems().get("кнопка продолжить")).click();
    }

    @Override
    public void close() {
        ((Clickable)getItems().get("кнопка закрытия модального окна")).click();
    }
}
