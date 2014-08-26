package project.lighthouse.autotests.objects.web.stockMovement.stockIn;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementWebObject;

public class StockInProduct extends StockMovementWebObject {

    public StockInProduct(WebElement element) {
        super(element);
    }

    @Override
    public void clickDeleteIcon() {
        clickDeleteIcon("delStockInProduct");
    }
}
