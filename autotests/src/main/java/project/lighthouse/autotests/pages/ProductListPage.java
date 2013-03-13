package project.lighthouse.autotests.pages;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;

@DefaultUrl("/?product/list")
public class ProductListPage extends ProductCreatePage{
	
	@FindBy(xpath = "//*[@name='product']")
	private WebElement productListItem;
	
	@FindBy(xpath = "//*[@lh_button='create']")
	private WebElement createNewProductButton;

	public ProductListPage(WebDriver driver) {
		super(driver);
	}

    public void CreateNewProductButtonClick(){
        $(createNewProductButton).click();
    }

    public WebElementFacade GetItemProductElement(String skuValue){
        String xpath = String.format("//../*[span[@name='sku' and text()='%s']]", skuValue);
        return $(productListItem).findBy(xpath);
    }
	
	public void ListItemClick(String skuValue){
        WebElementFacade productItem = GetItemProductElement(skuValue);
        productItem.click();
	}
	
	public void ListItemCheck(String skuValue){
        WebElementFacade productItem = GetItemProductElement(skuValue);
        productItem.shouldBePresent();
	}

    public void CheckProductWithSkuHasExpectedValue(String skuValue, String expectedValue){
        ListItemCheck(skuValue);
        WebElementFacade productItem = GetItemProductElement(skuValue);
        productItem.shouldContainText(expectedValue);
    }

    public void CheckProductWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        /*
        Need to implement method
         */
    }
}
