package project.lighthouse.autotests.pages.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.common.CommonView;

@DefaultUrl("/products")
public class ProductListPage extends ProductCreatePage {

    public static final String ITEM_NAME = "product";
    private static final String ITEM_SKU_NAME = "sku";

    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    public ProductListPage(WebDriver driver) {
        super(driver);
    }

    public void createNewProductButtonClick() {
        String xpath = "//a[@class='button']";
        findElement(By.xpath(xpath)).click();
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
