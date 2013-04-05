package project.lighthouse.autotests.pages.product;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPage;

import java.util.HashMap;
import java.util.Map;

@DefaultUrl("/product/create")
public class ProductCreatePage extends PageObject{

    public CommonPageInterface CommonPageInterface = new CommonPage(getDriver());
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

    @FindBy(xpath = "//*[@lh_card_back]")
    public WebElement productItemListLink;

    public Map<String, CommonItem> items = new HashMap<String, CommonItem>(){
        {
            put("sku", new CommonItem(skuField, CommonItem.types.input));
            put("name", new CommonItem(nameField, CommonItem.types.input));
            put("unit", new CommonItem(unitField, CommonItem.types.select));
            put("purchasePrice", new CommonItem(purchasePrice, CommonItem.types.input));
            put("vat", new CommonItem(vatField, CommonItem.types.select));
            put("barcode", new CommonItem(barCodeField, CommonItem.types.input));
            put("vendor", new CommonItem(vendorField, CommonItem.types.input));
            put("vendorCountry", new CommonItem(vendorCountryField, CommonItem.types.input));
            put("info", new CommonItem(infoField, CommonItem.types.textarea));
        }
    };

    public ProductCreatePage(WebDriver driver) {
        super(driver);
    }

    public void fieldInput(String elementName, String inputText){
        CommonItem item = items.get(elementName);
        CommonPageInterface.setValue(item, inputText);
	}

    public void fieldInput(ExamplesTable fieldInputTable){
        for (Map<String, String> row : fieldInputTable.getRows()){
            String elementName = row.get("elementName");
            String inputText = row.get("inputText");
            fieldInput(elementName, inputText);
        }
    }
	
	public void selectByValue(String elementName, String value){
        CommonItem item = items.get(elementName);
        CommonPageInterface.setValue(item, value);
	}
	
	public void createButtonClick(){
        findBy("//*[@lh_button='commit']").click();
        CommonPageInterface.checkCreateAlertSuccess(PRODUCT_NAME);
	}

    public void checkDropDownDefaultValue(String dropDownType, String expectedValue){
        WebElement element = items.get(dropDownType).getWebElement();
        String selectedValue = $(element).getSelectedValue();
            if (!selectedValue.equals(expectedValue)) {
                String errorMessage = String.format("The default value for '%s' dropDawn is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue);
                throw new AssertionError(errorMessage);
            }
    }

    public void checkFieldLength(String elementName, int fieldLength){
        WebElement element = items.get(elementName).getWebElement();
        CommonPageInterface.checkFieldLength(elementName, fieldLength, element);
    }
}
