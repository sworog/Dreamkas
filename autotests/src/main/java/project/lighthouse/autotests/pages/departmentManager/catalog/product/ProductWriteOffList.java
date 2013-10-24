package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.objects.product.ProductWriteOffListObject;
import project.lighthouse.autotests.objects.product.ProductWriteOffListObjectList;
import project.lighthouse.autotests.objects.product.abstractObjects.AbstractProductObjectList;

import java.util.List;

public class ProductWriteOffList extends CommonPageObject {

    public ProductWriteOffList(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("createdDateFormatted", new NonType(this, "createdDateFormatted"));
        items.put("quantity", new NonType(this, "quantity"));
        items.put("priceFormatted", new NonType(this, "priceFormatted"));
        items.put("totalPriceFormatted", new NonType(this, "totalPriceFormatted"));
    }

    private List<WebElement> getProductWriteOffListWebElements() {
        return waiter.getVisibleWebElements(By.xpath("//*[@name='writeOff']"));
    }

    public ProductWriteOffListObjectList getProductInvoiceListObjects() {
        ProductWriteOffListObjectList productInvoiceListObjects = new ProductWriteOffListObjectList();
        for (WebElement element : getProductWriteOffListWebElements()) {
            ProductWriteOffListObject productInvoiceListObject = new ProductWriteOffListObject(element);
            productInvoiceListObjects.add(productInvoiceListObject);
        }
        return productInvoiceListObjects;
    }

    public void productWriteOffListObjectClick(String number) {
        ProductWriteOffListObjectList productObjectLists = getProductInvoiceListObjects();
        for (AbstractProductObjectList abstractProductObjectList : productObjectLists) {
            ProductWriteOffListObject productWriteOffListObject = (ProductWriteOffListObject) abstractProductObjectList;
            if (productWriteOffListObject.getNumber().equals(number)) {
                productWriteOffListObject.click();
            }
        }
    }
}
