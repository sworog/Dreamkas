package project.lighthouse.autotests.objects.web.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.objects.web.invoiceProduct.InvoiceProductCollection;

public class StockInProductCollection extends InvoiceProductCollection {

    public StockInProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("stockInProduct"));
    }
}
