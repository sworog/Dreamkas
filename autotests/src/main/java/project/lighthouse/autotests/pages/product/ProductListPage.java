package project.lighthouse.autotests.pages.product;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;
import project.lighthouse.autotests.ICommonViewInterface;
import project.lighthouse.autotests.pages.common.ICommonView;

@DefaultUrl("/?product/list")
public class ProductListPage extends ProductCreatePage{
	
	@FindBy(name="product")
	private WebElement productListItem;
	
	@FindBy(xpath = "//*[@lh_button='create']")
	private WebElement createNewProductButton;

    private static final String XPATH = "//../*[span[@name='sku' and text()='%s']]";
    ICommonViewInterface iCommonViewInterface = new ICommonView(getDriver(), XPATH, productListItem);

	public ProductListPage(WebDriver driver) {
		super(driver);
	}

    public void CreateNewProductButtonClick(){
        $(createNewProductButton).click();
    }
	
	public void ListItemClick(String skuValue){
        iCommonViewInterface.ItemClick(skuValue);
	}
	
	public void ListItemCheck(String skuValue){
        iCommonViewInterface.ItemCheck(skuValue);
	}

    public void CheckProductWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        iCommonViewInterface.CheckInvoiceListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }
}
