package ru.dreamkas.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.stockMovement.stockIn.StockInProductCollection;
import ru.dreamkas.elements.bootstrap.buttons.TransparentBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.pages.stockMovement.modal.StockMovementModalPage;

public class StockInCreateModalWindow extends StockMovementModalPage {

    public StockInCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_stockIn']";
    }

    @Override
    public void createElements() {
        super.createElements();
        put("price", new Input(this, "//*[@name='price']"));
        put("кнопка 'Создать магазин'", new TransparentBtnFacade(this, "Создать магазин"));
        put("кнопка 'Создать товар'", new TransparentBtnFacade(this, "Создать товар"));
        put("плюсик, чтобы создать новый магазин", new NonType(this, "//*[contains(@data-modal, 'modal_store') and not(contains(@class, 'btn'))]"));
        put("плюсик, чтобы создать новый товар", new NonType(this, "//*[contains(@data-modal, 'modal_productForAutocomplete') and not(contains(@class, 'btn'))]"));

        putDefaultConfirmationOkButton(
                getConfirmationOkButton("Оприходовать"));
    }

    @Override
    public StockInProductCollection getProductCollection() {
        return new StockInProductCollection(getDriver());
    }

    @Override
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_stockInProducts");
    }
}
