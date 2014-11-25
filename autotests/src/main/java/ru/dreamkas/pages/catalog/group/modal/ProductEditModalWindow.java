package ru.dreamkas.pages.catalog.group.modal;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.pages.modal.ModalWindowPage;

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

        putDefaultConfirmationOkButton(
                new PrimaryBtnFacade(this, "Сохранить"));
    }

    public String getDeleteButtonText() {
        return ((CommonItem)getItems().get(ModalWindowPage.DEFAULT_DELETE_BUTTON)).getText();
    }
}
