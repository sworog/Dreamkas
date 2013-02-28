package project.lighthouse.autotests.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

@DefaultUrl("http://localhost:8008/index.xml?product;create")
public class OrderCreatePage extends PageObject{
	
	@FindBy(name="sku")
	private WebElement skuField;
	
	@FindBy(name="category")
	private WebElement categoryField;
	
	@FindBy(name="group")
	private WebElement groupField;
	
	@FindBy(name="undergroup")
	private WebElement undergroupField;
	
	@FindBy(name="name")
	private WebElement nameField;
	
	@FindBy(name="unit")
	private WebElement unitField;
	
	@FindBy(name="vat")
	private WebElement vatField;
	
	@FindBy(name="barcode")
	private WebElement barCodeField;
	
	@FindBy(name="purchasePrice")
	private WebElement purchasePrice;
	
	@FindBy(name="productCode")
	private WebElement productCodeField;
	
	@FindBy(name="vendor")
	private WebElement vendorField;
	
	@FindBy(name="vendorCountry")
	private WebElement vendorCountryField;
	
	@FindBy(name="info")
	private WebElement infoField;
	
	@FindBy(name="create")
	private WebElement createButton;
	
	public OrderCreatePage(WebDriver driver){
		super(driver);
	}
	
	public void input(String elementName, String inputText){
		WebElement element = getWebElement(elementName);
		$(element).type(inputText);
	}
	
	public void Select(String elementName, String value){
		WebElement element = getWebElement(elementName);
		$(element).selectByValue(value);
	}
	
	public void CreateButtonClick(){
		$(createButton).click();
	}
	
	public WebElement getWebElement(String name){		
		switch (name) {
		case "sku":
			return skuField;
		case "category": 
			return categoryField;
		case "group":
			return groupField;
		case "undergroupField":
			return undergroupField;
		case "name":
			return nameField;
		case "unit":
			return unitField;
		case "vat":
			return vatField;
		case "barcode":
			return barCodeField;
		case "purchasePrice":
			return purchasePrice;
		case "productCode":
			return productCodeField;
		case "vendor":
			return vendorCountryField;
		case "vendorCountry":
			return vendorCountryField;
		case "info":
			return infoField;
		default:
			return (WebElement) new NoSuchFieldException();
		}
	}
	

}
