package project.lighthouse.autotests.pages;

import net.thucydides.core.annotations.DefaultUrl;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

@DefaultUrl("http://localhost:8008/index.xml?product;edit")
public class ProductEditPage extends ProductCreatePage{
	
	@FindBy(xpath="//a[@mayak_button='reset']")
	private WebElement resetButton;
	
	public ProductEditPage(WebDriver driver) {
		super(driver);
	}
	
	public void FieldEdit(String ElementName, String inputText){
		WebElement element = getWebElement(ElementName);
		$(element).clear();
		$(element).type(inputText);
	}	
	
	public void CancelButtonClick(){
		$(resetButton).click();
	}
}
