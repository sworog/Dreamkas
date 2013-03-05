package project.lighthouse.autotests.pages;

import java.util.NoSuchElementException;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

@DefaultUrl("/?product;create")
public class ProductCreatePage extends PageObject{
	
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
	
	@FindBy(xpath="//button[@mayak_button='success']")
	private WebElement createButton;

    @FindBy(xpath = "//a[@mayak_button='reset']")
    private WebElement cancelButton;

    @FindBy(xpath = "//a[@mayak_card_back]")
    private WebElement productItemListLink;
	
	public ProductCreatePage(WebDriver driver){
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

    public void CancelButtonClick(){
        $(cancelButton).click();
    }

    public void GoToProductItemList(){
        $(productItemListLink).click();
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
			return vendorField;
		case "vendorCountry":
			return vendorCountryField;
		case "info":
			return infoField;
		default:
			return (WebElement) new NoSuchFieldException();
		}
	}

    public void CheckDropDownDefaultValue(String dropdownType, String expectedValue){
        WebElement element = getWebElement(dropdownType);
            if (!$(element).getSelectedValue().equals(expectedValue)) {
                String errorMessage = String.format("The default value for '%s' dropdawn is not '%s'", dropdownType, expectedValue);
                throw new AssertionError(errorMessage);
            }
    }
}
