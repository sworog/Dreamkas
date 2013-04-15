package project.lighthouse.autotests.pages.amount;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonView;
import project.lighthouse.autotests.pages.product.ProductListPage;

@DefaultUrl("/amount/list")
public class AmountListPage extends ProductListPage{

    private static final String ITEM_NAME = "amountItem";
    private static final String ITEM_SKU_NAME = "sku";

    @FindBy(name = "amount")
    public WebElement amountWebElement;

    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    public AmountListPage(WebDriver driver) {
        super(driver);
        items.put("amount", new CommonItem(amountWebElement, CommonItem.types.nonType));
    }

    public void checkAmountItemListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        commonViewInterface.checkListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }
}
