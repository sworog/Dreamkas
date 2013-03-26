package project.lighthouse.autotests.pages.product;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import project.lighthouse.autotests.ICommonPageInterface;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.ICommonPage;

import java.util.HashMap;
import java.util.Map;

@DefaultUrl("/?product/create")
public class ProductCreatePage extends PageObject{

    public ICommonPageInterface ICommonPageInterface = new ICommonPage(getDriver());
    private static final String PRODUCT_NAME = "product";
	
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

    public Map<String, CommonItem> items = new HashMap<String, CommonItem>(){
        {
            put("sku", new CommonItem(skuField, CommonItem.types.input));
            put("name", new CommonItem(nameField, CommonItem.types.input));
            put("unit", new CommonItem(unitField, CommonItem.types.checkbox));
            put("purchasePrice", new CommonItem(purchasePrice, CommonItem.types.input));
            put("vat", new CommonItem(vatField, CommonItem.types.checkbox));
            put("barcode", new CommonItem(barCodeField, CommonItem.types.input));
            put("vendor", new CommonItem(vendorField, CommonItem.types.input));
            put("vendorCountry", new CommonItem(vendorCountryField, CommonItem.types.input));
            put("info", new CommonItem(infoField, CommonItem.types.textarea));
        }
    };

    public ProductCreatePage(WebDriver driver) {
        super(driver);
    }

    public void FieldInput(String elementName, String inputText){
        CommonItem item = items.get(elementName);
        ICommonPageInterface.SetValue(item, inputText);
	}

    public void FieldInput(ExamplesTable fieldInputTable){
        for (Map<String, String> row : fieldInputTable.getRows()){
            String elementName = row.get("elementName");
            String inputText = row.get("inputText");
            FieldInput(elementName, inputText);
        }
    }
	
	public void SelectByValue(String elementName, String value){
        CommonItem item = items.get(elementName);
        ICommonPageInterface.SetValue(item, value);
	}
	
	public void CreateButtonClick(){
		$(createButton).click();
        ICommonPageInterface.CheckCreateAlertSuccess(PRODUCT_NAME);
	}

    public void CheckDropDownDefaultValue(String dropDownType, String expectedValue){
        WebElement element = items.get(dropDownType).GetWebElement();
        String selectedValue = $(element).getSelectedValue();
            if (!selectedValue.equals(expectedValue)) {
                String errorMessage = String.format("The default value for '%s' dropDawn is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue);
                throw new AssertionError(errorMessage);
            }
    }

    public void CheckFieldLength(String elementName, int fieldLength){
        WebElement element = items.get(elementName).GetWebElement();
        ICommonPageInterface.CheckFieldLength(elementName, fieldLength, element);
    }
}
