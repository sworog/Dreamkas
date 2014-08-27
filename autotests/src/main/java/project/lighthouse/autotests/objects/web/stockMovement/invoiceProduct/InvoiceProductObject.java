package project.lighthouse.autotests.objects.web.stockMovement.invoiceProduct;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.compare.CompareResults;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementProductObject;

import java.util.Map;

public class InvoiceProductObject extends StockMovementProductObject {

    private String priceEntered;

    public InvoiceProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        priceEntered = getElement().findElement(By.name("priceEntered")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        totalPrice = getElement().findElement(By.name("totalPrice")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("priceEntered", priceEntered, row.get("priceEntered"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("totalPrice", totalPrice, row.get("totalPrice"));
    }

    public void clickDeleteIcon() {
        clickDeleteIcon("delInvoiceProduct");
    }
}
