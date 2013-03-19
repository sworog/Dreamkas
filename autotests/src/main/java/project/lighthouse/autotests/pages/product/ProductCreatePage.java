package project.lighthouse.autotests.pages.product;

import net.thucydides.core.pages.WebElementFacade;
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
    public WebElement skuField;
	
	@FindBy(name="category")
    private WebElement categoryField;
	
	@FindBy(name="group")
    private WebElement groupField;
	
	@FindBy(name="underGroup")
    private WebElement underGroupField;
	
	@FindBy(name="name")
    public WebElement nameField;
	
	@FindBy(name="units")
    public WebElement unitField;
	
	@FindBy(name="vat")
    public WebElement vatField;
	
	@FindBy(name="barcode")
    public WebElement barCodeField;
	
	@FindBy(name="purchasePrice")
    public WebElement purchasePrice;
	
	@FindBy(name="productCode")
    public WebElement productCodeField;
	
	@FindBy(name="vendor")
    public WebElement vendorField;
	
	@FindBy(name="vendorCountry")
    public WebElement vendorCountryField;
	
	@FindBy(name="info")
    public WebElement infoField;
	
	@FindBy(xpath="//*[@lh_button='commit']")
	private WebElement createButton;

    @FindBy(xpath = "//*[@lh_card_back]")
    private WebElement productItemListLink;

    public ProductCreatePage(WebDriver driver) {
        super(driver);
    }

    public void FieldInput(String elementName, String inputText){
        WebElement element = GetWebElement(elementName);
        $(element).type(inputText);
	}

    public void FieldInput(ExamplesTable fieldInputTable){
        for (Map<String, String> row : fieldInputTable.getRows()){
            String elementName = row.get("elementName");
            String inputText = row.get("inputText");
            FieldInput(elementName, inputText);
        }
    }
	
	public void SelectByValue(String elementName, String value){
		WebElement element = GetWebElement(elementName);
		$(element).selectByValue(value);
	}
	
	public void CreateButtonClick(){
		$(createButton).click();
        CreateButtonNotSuccessAlertCheck("product");
	}

    public void CreateButtonNotSuccessAlertCheck(String name){
        boolean isAlertPresent;
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
                String errorMessage = String.format("Can't create new '%s'. Error alert is present. Alert text: %s", name, alertText);
                throw new AssertionError(errorMessage);
            }
        }
    }

    public WebElement GetWebElement(String name){
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
			return (WebElement) new AssertionError("No such value for GetWebElement method!");
		}
	}

    public void CheckDropDownDefaultValue(String dropDownType, String expectedValue){
        WebElement element = GetWebElement(dropDownType);
        String selectedValue = $(element).getSelectedValue();
            if (!selectedValue.equals(expectedValue)) {
                String errorMessage = String.format("The default value for '%s' dropDawn is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue);
                throw new AssertionError(errorMessage);
            }
    }

    public void CheckFieldLength(String elementName, int fieldLength){
        WebElement element = GetWebElement(elementName);
        int length = 0;
        switch (element.getTagName()){
            case "input":
                length = $(element).getTextValue().length();
                break;
            case "textarea":
                length = $(element).getValue().length();
                break;
            default:
                length = $(element).getText().length();
                break;
        }
        if(length != fieldLength){
            String errorMessage = String.format("The '%s' field doesn't contains '%s' symbols. It actually contains '%s' symbols.", elementName, fieldLength, length);
            throw new AssertionError(errorMessage);
        }
    }

    public void CheckErrorMessages(ExamplesTable errorMessageTable){
        CheckErrorMessagesLogic(errorMessageTable);
    }

    public void CheckNoErrorMessages(){
        String xpath = "//*[@lh_field_error]";
        if(isPresent(xpath)){
            String errorMessage = "There are error field validation messages on the page!";
            throw new AssertionError(errorMessage);
        }
    }

    public boolean isPresent(String xpath){
        try {
            WebElementFacade errorMessageWebElement = findBy(xpath);
            return true;
        }
        catch (Exception e){
            return false;
        }
    }

    public void CheckErrorMessagesLogic(ExamplesTable errorMessageTable){
        for (Map<String, String> row : errorMessageTable.getRows()){
            String expectedErrorMessage = row.get("error message");
            String xpath = String.format("//*[contains(@lh_field_error,'%s')]", expectedErrorMessage);
            if(!isPresent(xpath)){
                String errorMessage = "There are no error field validation messages on the page!";
                throw new AssertionError(errorMessage);
            }
        }
    }
}
