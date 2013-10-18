package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.objects.ProductInvoiceListObject;
import project.lighthouse.autotests.objects.ProductInvoiceListObjectsList;

import java.util.List;

public class ProductInvoicesList extends CommonPageObject {

    public ProductInvoicesList(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("date", new NonType(this, "date"));
        items.put("number", new NonType(this, "number"));
        items.put("price", new NonType(this, "price"));
    }

    private List<WebElement> getProductInvoicesListWebElements() {
        return waiter.getVisibleWebElements(By.xpath("//*[@class='element']"));
    }

    public ProductInvoiceListObjectsList getProductInvoiceListObjects() {
        ProductInvoiceListObjectsList productInvoiceListObjects = new ProductInvoiceListObjectsList();
        for (WebElement element : getProductInvoicesListWebElements()) {
            ProductInvoiceListObject productInvoiceListObject = new ProductInvoiceListObject(getDriver(), element);
            productInvoiceListObjects.add(productInvoiceListObject);
        }
        return productInvoiceListObjects;
    }
}
