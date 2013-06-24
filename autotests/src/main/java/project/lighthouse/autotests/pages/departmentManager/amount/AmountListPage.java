package project.lighthouse.autotests.pages.departmentManager.amount;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.NonType;
import project.lighthouse.autotests.pages.commercialManager.product.ProductListPage;

@DefaultUrl("/balance")
public class AmountListPage extends ProductListPage {

    public static final String ITEM_NAME = "amountItem";
    private static final String ITEM_SKU_NAME = "sku";

    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    public AmountListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        items.put("amount", new NonType(this, "amount"));
        items.put("amounts sku", new NonType(this, "sku"));
        items.put("amounts name", new NonType(this, "name"));
        items.put("amounts vendor", new NonType(this, "vendor"));
        items.put("amounts vendorCountry", new NonType(this, "vendorCountry"));
        items.put("amounts purchasePrice", new NonType(this, "lastPurchasePrice"));
        items.put("amounts averagePrice", new NonType(this, "averagePurchasePrice"));
        items.put("amounts amount", new NonType(this, "amount"));
        items.put("amounts units", new NonType(this, "units"));
    }

    public void checkAmountItemListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue) {
        commonViewInterface.checkListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        By findBy = items.get(elementName).getFindBy();
        commonViewInterface.checkListItemHasExpectedValueByFindByLocator(value, elementName, findBy, expectedValue);
    }
}
