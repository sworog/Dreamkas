package project.lighthouse.autotests.pages;

import net.thucydides.core.annotations.DefaultUrl;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

@DefaultUrl("http://localhost:8008/index.xml?product;edit")
public class OrderEditPage extends OrderCreatePage{

	@FindBy(name="success")
	private WebElement editButton;
	
	@FindBy(name="reset")
	private WebElement resetButton;
	
	public OrderEditPage(WebDriver driver) {
		super(driver);
	}
	
	public void FieldEdit(String ElementName, String inputText){
		WebElement element = getWebElement(ElementName);
		$(element).clear();
		$(element).type(inputText);
	}
	
	public void EditbuttonClick(){
		$(editButton).click();
	}
	
	public void CancelButtonClick(){
		$(resetButton).click();
	}
	
	

}
