package project.lighthouse.autotests.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

@DefaultUrl("http://localhost:8008/index.xml?product-list")
public class OrdersListPage extends PageObject{
	
	@FindBy()
	private WebElement orderListItem;
	
	@FindBy()
	private WebElement searchInputField;

	public OrdersListPage(WebDriver driver) {
		super(driver);
	}
	
	public void ListItemClick(){
		$(orderListItem).click();
	}
	
	public void ListItemChecks(){
		$(orderListItem).isPresent();		
	}
	
	/*Example checks method by xpath selector*/
	public void ListItemChecksByXpath(String nameValue, String priceValue){
		StringBuilder builder = new StringBuilder("//*[@name = '");
	    builder.append(nameValue);
	    builder.append("' and @price='");
	    builder.append(priceValue);
	    builder.append("']");
		$(orderListItem).findBy(builder.toString()).shouldBePresent();
		
	}
	
	public void Search(String searchInput){
		$(searchInputField).typeAndEnter(searchInput);
	}
	

}
