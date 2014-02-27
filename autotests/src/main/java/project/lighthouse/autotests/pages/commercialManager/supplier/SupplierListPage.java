package project.lighthouse.autotests.pages.commercialManager.supplier;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.supplier.SupplierObjectCollection;

/**
 * Page object class representing supplier list page
 */
@DefaultUrl("/suppliers")
public class SupplierListPage extends CommonPageObject {

    public SupplierListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public SupplierObjectCollection getSupplierObjectCollection() {
        return new SupplierObjectCollection(getDriver(), By.name("supplierData"));
    }
}
