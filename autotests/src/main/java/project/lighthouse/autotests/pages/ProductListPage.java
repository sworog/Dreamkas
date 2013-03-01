package project.lighthouse.autotests.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

@DefaultUrl("http://localhost:8008/index.xml?product-list")
public class ProductListPage extends PageObject{
	
	@FindBy()
	private WebElement productListItem;
	
	@FindBy()
	private WebElement searchInputField;

	public ProductListPage(WebDriver driver) {
		super(driver);
	}
	
	public void ListItemClick(){
		$(productListItem).click();
	}
	
	public void ListItemChecks(){
		$(productListItem).isPresent();		
	}
	
	/*Example checks method by xpath selector*/
	public void ListItemChecksByXpath(String nameValue, String priceValue){
		StringBuilder builder = new StringBuilder("//*[@name = '");
	    builder.append(nameValue);
	    builder.append("' and @price='");
	    builder.append(priceValue);
	    builder.append("']");
		$(productListItem).findBy(builder.toString()).shouldBePresent();
		
	}
	
	public void Search(String searchInput){
		$(searchInputField).typeAndEnter(searchInput);
	}	
}
