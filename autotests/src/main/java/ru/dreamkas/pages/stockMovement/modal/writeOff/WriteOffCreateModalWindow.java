package ru.dreamkas.pages.stockMovement.modal.writeOff;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.writeOffProduct.WriteOffProductCollection;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.pages.stockMovement.modal.StockMovementModalPage;

public class WriteOffCreateModalWindow extends StockMovementModalPage {

    public WriteOffCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_writeOff']";
    }

    @Override
    public void createElements() {
        super.createElements();
        put("cause", new Input(this, "//*[@class='form_stockMovementProducts__controls']//*[@name='cause']"));

        putDefaultConfirmationOkButton(
                getConfirmationOkButton("Списать"));
    }

    @Override
    public WriteOffProductCollection getProductCollection() {
        return new WriteOffProductCollection(getDriver());
    }

    @Override
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_writeOffProducts");
    }
}
