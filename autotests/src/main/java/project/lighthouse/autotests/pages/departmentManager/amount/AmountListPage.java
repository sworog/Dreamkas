package project.lighthouse.autotests.pages.departmentManager.amount;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.pages.commercialManager.product.ProductListPage;

@DefaultUrl("/balance")
public class AmountListPage extends ProductListPage {

    public static final String ITEM_NAME = "inventoryItem";
    private static final String ITEM_SKU_NAME = "sku";

    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    public AmountListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        put("amount", new NonType(this, "inventory"));
        put("amounts sku", new NonType(this, "sku"));
        put("amounts name", new NonType(this, "name"));
        put("amounts vendor", new NonType(this, "vendor"));
        put("amounts vendorCountry", new NonType(this, "vendorCountry"));
        put("amounts purchasePrice", new NonType(this, "lastPurchasePrice"));
        put("amounts averagePrice", new NonType(this, "averagePurchasePrice"));
        put("amounts amount", new NonType(this, "inventory"));
        put("amounts units", new NonType(this, "units"));
    }

    @Deprecated
    public void checkAmountItemListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue) {
        commonViewInterface.checkListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        By findBy = getItems().get(elementName).getFindBy();
        commonViewInterface.checkListItemHasExpectedValueByFindByLocator(value, elementName, findBy, expectedValue);
    }
}
