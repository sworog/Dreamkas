package project.lighthouse.autotests.pages;

import java.util.NoSuchElementException;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

@DefaultUrl("index.xml?product-1")
public class ProductCardView extends ProductCreatePage{
	
	@FindBy(xpath="//")
	private WebElement nameValue;
	
	@FindBy(xpath="//")
	private WebElement vendorValue;
	
	@FindBy()
	private WebElement vendorCountryValue;
	
	@FindBy()
	private WebElement purchasePriceValue;
	
	@FindBy()
	private WebElement unitValue;
	
	@FindBy()
	private WebElement vatValue;
	
	@FindBy()
	private WebElement infoValue;
	
	public ProductCardView(WebDriver driver) {
		super(driver);
	}
	
	public void CheckValue(String elementName, String expectedValue){
		WebElement element = getWebElement(elementName);
		$(element).containsText(expectedValue);
	}
	
	public void CheckValue(String expectedValue){
		WebElement valueToVerify = (WebElement) findBy("//xpathToEachValue");
		$(valueToVerify).shouldContainText(expectedValue);
	}
	
	public WebElement getWebElement(String elementName){
		switch (elementName) {
		case "maker":
			return vendorValue;
		case "purchasePrice":
			return purchasePriceValue;
		default:
			return (WebElement) new NoSuchElementException();
		}		
	}
}
