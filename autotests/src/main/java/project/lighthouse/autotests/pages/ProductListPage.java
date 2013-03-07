package project.lighthouse.autotests.pages;

import net.thucydides.core.pages.AnyPage;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

@DefaultUrl("/?product;list")
public class ProductListPage extends PageObject{
	
	@FindBy(xpath = "//a[contains(@id,'product')]")
	private WebElement productListItem;
	
	@FindBy(xpath = "//a[@lh_button='create']")
	private WebElement createNewProductButton;

	public ProductListPage(WebDriver driver) {
		super(driver);
	}

    public void CreateNewProductButtonClick(){
        $(createNewProductButton).click();
    }

    public WebElementFacade GetItemProductElement(String skuValue){
        String xpath = String.format("//../a[span[text()='%s']]", skuValue);
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

    public void isRequiredPageOpen(){
        String defaultUrl = ProductListPage.class.getAnnotations()[0].toString().substring(50, 64);
        String actualUrl = getDriver().getCurrentUrl();
        if(!actualUrl.contains(defaultUrl)){
            String errorMessage = String.format("The product list page is not open!\nActual url: %s\nExpected url: %s", actualUrl, defaultUrl);
            throw new AssertionError(errorMessage);
        }
    }
}
