package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.bootstrap.buttons.TransparentBtnFacade;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.pages.catalog.group.modal.ProductCreateModalWindow;

public class InvoiceProductCreateModalWindow extends ProductCreateModalWindow {

    public InvoiceProductCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        put("кнопка 'Создать группу'", new TransparentBtnFacade(this, "Создать группу"));
        put("плюсик, чтобы создать новую группу", new NonType(this, "//*[contains(@data-modal, 'modal_group') and not(contains(@class, 'btn'))]"));

    }

    @Override
    public String modalWindowXpath() {
        return "//*[contains(@id, 'modal_product') and contains(@class, 'modal_visible')]";
    }
}
