package project.lighthouse.autotests.pages.departmentManager.catalog.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.objects.web.product.WriteOffListObjectList;

public class ProductWriteOffList extends CommonPageObject {

    public ProductWriteOffList(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("createdDateFormatted", new NonType(this, "createdDateFormatted"));
        put("quantity", new NonType(this, "quantity"));
        put("priceFormatted", new NonType(this, "priceFormatted"));
        put("totalPriceFormatted", new NonType(this, "totalPriceFormatted"));
    }

    public WriteOffListObjectList getProductInvoiceListObjects() {
        return new WriteOffListObjectList(getDriver(), By.name("writeOff"));
    }

    public void productWriteOffListObjectClick(String number) {
        getProductInvoiceListObjects().clickByLocator(number);
    }
}
