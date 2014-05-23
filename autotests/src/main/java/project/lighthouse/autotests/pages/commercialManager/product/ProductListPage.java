package project.lighthouse.autotests.pages.commercialManager.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.objects.web.product.ProductObjectCollection;

@DefaultUrl("/products")
public class ProductListPage extends CommonPageObject {

    @Override
    public void createElements() {
        put("sku");
        put("name");
        put("vendor");
        put("vendorCountry");
        put("purchasePrice");
    }

    public ProductListPage(WebDriver driver) {
        super(driver);
    }

    public void createNewProductButtonClick() {
        new ButtonFacade(this, "Добавить товар").click();
    }

    public ProductObjectCollection getProductObjectCollection() {
        return new ProductObjectCollection(getDriver(), By.name("product"));
    }
}
