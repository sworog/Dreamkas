package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import junit.framework.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.objects.product.ProductInvoiceListObject;
import project.lighthouse.autotests.objects.product.ProductInvoiceListObjectsList;
import project.lighthouse.autotests.objects.product.abstractObjects.AbstractProductObjectList;

import java.util.List;

public class ProductInvoicesList extends CommonPageObject {

    public ProductInvoicesList(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("acceptanceDateFormatted", new NonType(this, "acceptanceDateFormatted"));
        items.put("quantity", new NonType(this, "quantity"));
        items.put("priceFormatted", new NonType(this, "priceFormatted"));
        items.put("totalPriceFormatted", new NonType(this, "totalPriceFormatted"));
    }

    private List<WebElement> getProductInvoicesListWebElements() {
        return waiter.getVisibleWebElements(By.xpath("//*[@name='invoice']"));
    }

    public ProductInvoiceListObjectsList getProductInvoiceListObjects() {
        ProductInvoiceListObjectsList productInvoiceListObjects = new ProductInvoiceListObjectsList();
        for (WebElement element : getProductInvoicesListWebElements()) {
            ProductInvoiceListObject productInvoiceListObject = new ProductInvoiceListObject(element);
            productInvoiceListObjects.add(productInvoiceListObject);
        }
        return productInvoiceListObjects;
    }

    public void invoiceSkuClick(String sku) {
        Boolean found = false;
        for (AbstractProductObjectList abstractProductObjectList : getProductInvoiceListObjects()) {
            ProductInvoiceListObject productInvoiceListObject = (ProductInvoiceListObject) abstractProductObjectList;
            if (productInvoiceListObject.getInvoiceSku().equals(sku)) {
                found = true;
                productInvoiceListObject.click();
            }
        }
        if (!found) {
            String errorMessage = String.format("There is no invoice with '%s' to click!", sku);
            Assert.fail(errorMessage);
        }
    }
}
