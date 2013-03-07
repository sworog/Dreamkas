package project.lighthouse.autotests.pages;

import java.util.List;
import java.util.NoSuchElementException;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;

@DefaultUrl("/index.xml?product")
public class ProductCardView extends ProductCreatePage{

    @FindBy(xpath = "//span[@title='Название']")
	private WebElement nameValue;

    @FindBy(xpath = "//span[@title='Артикул']")
    private WebElement skuValue;
	
	@FindBy(xpath="//div[@lh_prop]")
	private List<WebElement> lh_block_Values;
	
	public ProductCardView(WebDriver driver) {
		super(driver);
	}
	
	public void CheckValue(String elementName, String expectedValue){
        switch (elementName){
            case "sku":
            case "name":
                WebElement element = getWebElement(elementName);
                $(element).shouldContainText(expectedValue);
                break;
            default:
                boolean IsContains = false;
                for (WebElement webElement : lh_block_Values) {
                    if ($(webElement).containsText(expectedValue)){
                        IsContains = true;
                    }
                }
                if (!IsContains){
                    String errorMessage = String.format("The value '%s' is not '%s'", elementName, expectedValue);
                    throw new AssertionError(errorMessage);
                }
                break;
        }
	}
	
	public WebElement getWebElement(String elementName){
		switch (elementName) {
		case "sku":
			return skuValue;
		case "name":
			return nameValue;
		default:
            return (WebElement)new AssertionError("No such value!");
		}		
	}
}
