package project.lighthouse.autotests.pages.product;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonView;

@DefaultUrl("/product/list")
public class ProductListPage extends ProductCreatePage{
	
	@FindBy(name="product")
	private WebElement productListItem;

    private static final String XPATH = "//../*[span[@name='sku' and normalize-space(text())='%s']]";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), XPATH, productListItem);

	public ProductListPage(WebDriver driver) {
		super(driver);
	}

    public void createNewProductButtonClick(){
        findBy("//*[@lh_button='create']").click();
    }
	
	public void listItemClick(String skuValue){
        commonViewInterface.itemClick(skuValue);
	}
	
	public void listItemCheck(String skuValue){
        commonViewInterface.itemCheck(skuValue);
	}

    public void checkProductWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        commonViewInterface.checkInvoiceListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }
}
