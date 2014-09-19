package project.lighthouse.autotests.collection.stockMovement.stockIn;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.stockMovement.StockMovementProductObject;

public class StockInProductObject extends StockMovementProductObject {

    public StockInProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void clickDeleteIcon() {
        clickDeleteIcon("delStockInProduct");
    }
}
