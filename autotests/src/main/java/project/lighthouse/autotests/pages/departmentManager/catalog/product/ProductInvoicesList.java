package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import junit.framework.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.notApi.product.InvoiceCollection;
import project.lighthouse.autotests.objects.notApi.product.InvoiceListObject;

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

    public InvoiceCollection getProductInvoiceListObjects() {
        return new InvoiceCollection(getDriver(), By.name("invoice"));
    }

    public void invoiceSkuClick(String sku) {
        Boolean found = false;
        for (AbstractObject abstractObject : getProductInvoiceListObjects()) {
            InvoiceListObject productInvoiceListObject = (InvoiceListObject) abstractObject;
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
