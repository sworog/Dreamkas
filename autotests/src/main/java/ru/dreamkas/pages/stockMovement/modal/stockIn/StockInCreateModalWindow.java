package ru.dreamkas.pages.stockMovement.modal.stockIn;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.stockMovement.stockIn.StockInProductCollection;
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
    public void confirmationOkClick() {
        confirmationOkClick("Оприходовать");
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
