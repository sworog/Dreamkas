package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.objects.web.product.WriteOffListObjectList;

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

    public WriteOffListObjectList getProductInvoiceListObjects() {
        return new WriteOffListObjectList(getDriver(), By.name("writeOff"));
    }

    public void productWriteOffListObjectClick(String number) {
        getProductInvoiceListObjects().clickByLocator(number);
    }
}
