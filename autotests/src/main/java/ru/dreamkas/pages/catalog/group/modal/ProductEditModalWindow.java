package ru.dreamkas.pages.catalog.group.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;

/**
 * Edit product modal window
 */
public class ProductEditModalWindow extends ProductCreateModalWindow {

    public ProductEditModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();

        put("заголовок успешного удаления", new NonType(this, "//*[@name='successRemoveTitle']"));
        put("название удаленного товара", new NonType(this, "//*[@name='removedProductName']"));
        put("кнопка продолжения работы", new NonType(this, "//*[@name='removeContinue']"));
    }

    protected WebElement findDeleteButton() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='removeLink']"));
    }

    public void deleteButtonClick() {
        findDeleteButton().click();
    }

    public String getDeleteButtonText() {
        return findDeleteButton().getText();
    }

    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='confirmLink__confirmation']//*[@class='removeLink form_product__removeLink']")).click();
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Сохранить").click();
    }

    @Override
    public void continueButtonClick() {
        findVisibleElement(By.name("removeContinue")).click();
    }
}
