package project.lighthouse.autotests.pages;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Alert;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;

import java.util.Map;

@DefaultUrl("/?product/create")
public class ProductCreatePage extends PageObject{
	
	@FindBy(name="sku")
	private WebElement skuField;
	
	@FindBy(name="category")
	private WebElement categoryField;
	
	@FindBy(name="group")
	private WebElement groupField;
	
	@FindBy(name="underGroup")
	private WebElement underGroupField;
	
	@FindBy(name="name")
	private WebElement nameField;
	
	@FindBy(name="units")
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
	
	@FindBy(xpath="//button[@lh_button='success']")
	private WebElement createButton;

    @FindBy(xpath = "//a[@lh_button='reset']")
    private WebElement cancelButton;

    @FindBy(xpath = "//a[@lh_card_back]")
    private WebElement productItemListLink;
	
	public ProductCreatePage(WebDriver driver){
		super(driver);
	}
	
	public void FieldType(String elementName, String inputText){
		FieldAction(elementName, inputText, "create");
	}

    public void FieldEdit(String elementName, String inputText){
        FieldAction(elementName, inputText, "edit");
    }

    public void FieldAction(String elementName, String inputText, String action){
        WebElement element = getWebElement(elementName);
            if (action.equals("edit")){
            $(element).clear();
            }
        $(element).type(inputText);
    }

    public void FieldType(ExamplesTable fieldInputTable){
        FieldTypeAction(fieldInputTable, "input");
    }

    public void FieldEdit(ExamplesTable fieldInputTable){
        FieldTypeAction(fieldInputTable, "edit");
    }

    public void FieldTypeAction(ExamplesTable fieldInputTable, String action){
        for (Map<String, String> row : fieldInputTable.getRows()){
            String elementName = row.get("elementName");
            String inputText = row.get("inputText");
            switch (action){
                case "edit":
                    FieldType(elementName, inputText);
                    break;
                case "input":
                    FieldEdit(elementName, inputText);
                    break;
                default:
                    String errorMessage = "No such value!";
                    throw new AssertionError(errorMessage);
            }
        }
    }
	
	public void SelectByValue(String elementName, String value){
		WebElement element = getWebElement(elementName);
		$(element).selectByValue(value);
	}
	
	public void CreateButtonClick(){
		$(createButton).click();
        CreateButtonNotSuccessAlertCheck();
	}

    public void CreateButtonNotSuccessAlertCheck(){
        boolean isAlertPresent = false;
        Alert alert = null;
        try {
            alert = getAlert();
            isAlertPresent = true;
        }
        catch (Exception e){
            isAlertPresent = false;
        }
        if(isAlertPresent){
            String errorAlertMessage = "Ошибка";
            String alertText = alert.getText();
            if(alertText.contains(errorAlertMessage)){
                String errorMessage = String.format("Can't create new product. Error alert is present. Alert text: %s", alertText);
                throw new AssertionError(errorMessage);
            }
        }
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
		case "underGroupField":
			return underGroupField;
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
			return (WebElement) new AssertionError("No such value for getWebElement method!");
		}
	}

    public void CheckDropDownDefaultValue(String dropDownType, String expectedValue){
        WebElement element = getWebElement(dropDownType);
        String selectedValue = $(element).getSelectedValue();
            if (!selectedValue.equals(expectedValue)) {
                String errorMessage = String.format("The default value for '%s' dropDawn is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue);
                throw new AssertionError(errorMessage);
            }
    }
}
