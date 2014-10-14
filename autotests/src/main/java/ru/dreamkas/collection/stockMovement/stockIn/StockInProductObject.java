package ru.dreamkas.collection.stockMovement.stockIn;

import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.stockMovement.StockMovementProductObject;

public class StockInProductObject extends StockMovementProductObject {

    public StockInProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void clickDeleteIcon() {
        clickDeleteIcon("delStockInProduct");
    }
}
