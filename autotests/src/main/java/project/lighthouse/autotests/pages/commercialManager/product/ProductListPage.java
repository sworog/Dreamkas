package project.lighthouse.autotests.pages.commercialManager.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;

@DefaultUrl("/products")
public class ProductListPage extends ProductCreatePage {

    public static final String ITEM_NAME = "product";
    private static final String ITEM_SKU_NAME = "sku";

    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    public ProductListPage(WebDriver driver) {
        super(driver);
    }

    public void createNewProductButtonClick() {
        new ButtonFacade(getDriver(), "Добавить товар").click();
    }

    public void listItemClick(String skuValue) {
        commonViewInterface.itemClick(skuValue);
    }

    public void listItemCheck(String skuValue) {
        commonViewInterface.itemCheck(skuValue);
    }

    public void listItemCheckIsNotPresent(String value) {
        commonViewInterface.itemCheckIsNotPresent(value);
    }

    public void checkProductWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue) {
        commonViewInterface.checkListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }
}
