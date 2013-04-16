package project.lighthouse.autotests.pages.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPageObject;
import project.lighthouse.autotests.pages.elements.Input;
import project.lighthouse.autotests.pages.elements.Select;
import project.lighthouse.autotests.pages.elements.Textarea;

import java.util.Map;

@DefaultUrl("/product/create")
public class ProductCreatePage extends CommonPageObject {

    private static final String PRODUCT_NAME = "product";

    @FindBy(xpath = "//*[@lh_card_back]")
    public WebElement productItemListLink;

    public ProductCreatePage(WebDriver driver) {
        super(driver);
    }

    public void createElements() {
        items.put("sku", new Input(this, "sku"));
        items.put("name", new Input(this, "name"));
        items.put("unit", new Select(this, "units"));
        items.put("purchasePrice", new Input(this, "purchasePrice"));
        items.put("vat", new Select(this, "vat"));
        items.put("barcode", new Input(this, "barcode"));
        items.put("vendor", new Input(this, "vendor"));
        items.put("vendorCountry", new Input(this, "vendorCountry"));
        items.put("info", new Textarea(this, "info"));
    }

    public void fieldInput(String elementName, String inputText) {
        items.get(elementName).setValue(inputText);
    }

    public void fieldInput(ExamplesTable fieldInputTable) {
        for (Map<String, String> row : fieldInputTable.getRows()) {
            String elementName = row.get("elementName");
            String inputText = row.get("inputText");
            fieldInput(elementName, inputText);
        }
    }

    public void selectByValue(String elementName, String value) {
        items.get(elementName).setValue(value);
    }

    public void createButtonClick() {
        findBy("//*[@lh_button='commit']").click();
        commonPage.checkCreateAlertSuccess(PRODUCT_NAME);
    }

    public void checkDropDownDefaultValue(String dropDownType, String expectedValue) {
        WebElement element = items.get(dropDownType).getWebElement();
        String selectedValue = $(element).getSelectedValue();
        if (!selectedValue.equals(expectedValue)) {
            String errorMessage = String.format("The default value for '%s' dropDown is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue);
            throw new AssertionError(errorMessage);
        }
    }

    public void checkFieldLength(String elementName, int fieldLength) {
        CommonItem item = items.get(elementName);
        commonPage.checkFieldLength(elementName, fieldLength, item);
    }
}
