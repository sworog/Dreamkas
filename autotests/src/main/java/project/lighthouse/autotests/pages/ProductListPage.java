package project.lighthouse.autotests.pages;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

@DefaultUrl("/?product;list")
public class ProductListPage extends PageObject{
	
	@FindBy(xpath = "//a[contains(@id,'product')]")
	private WebElement productListItem;
	
	@FindBy(xpath = "//[@mayak_button='create']")
	private WebElement createNewProductButton;

	public ProductListPage(WebDriver driver) {
		super(driver);
	}

    public void CreateNewProductButtonClick(){
        $(createNewProductButton).click();
    }

    public WebElementFacade GetItemProductElement(String skuValue){
        String xpath = String.format("/../a[span[text()='%s']]", skuValue);
        return $(productListItem).findBy(xpath);
    }
	
	public void ListItemClick(String skuValue){
        WebElementFacade productItem = GetItemProductElement(skuValue);
        productItem.click();
	}
	
	public void ListItemChecks(String skuValue){
        WebElementFacade productItem = GetItemProductElement(skuValue);
        productItem.shouldBePresent();
	}

    public void CheckProductWithSkuHasExpectedValue(String skuValue, String expectedValue){
        ListItemChecks(skuValue);
        WebElementFacade productItem = GetItemProductElement(skuValue);
        productItem.shouldContainText(expectedValue);
    }
}
