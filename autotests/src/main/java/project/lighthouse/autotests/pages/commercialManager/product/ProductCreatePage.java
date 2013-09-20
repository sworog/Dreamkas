package project.lighthouse.autotests.pages.commercialManager.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.*;

import java.util.Map;

import static junit.framework.Assert.*;

@DefaultUrl("/products/create")
public class ProductCreatePage extends CommonPageObject {

    public ProductCreatePage(WebDriver driver) {
        super(driver);
    }

    public void createElements() {
        items.put("sku", new Input(this, "sku"));
        items.put("name", new Input(this, "name"));
        items.put("unit", new SelectByValue(this, "units"));
        items.put("purchasePrice", new Input(this, "purchasePrice"));
        items.put("vat", new SelectByValue(this, "vat"));
        items.put("barcode", new Input(this, "barcode"));
        items.put("vendor", new Input(this, "vendor"));
        items.put("vendorCountry", new Input(this, "vendorCountry"));
        items.put("info", new Textarea(this, "info"));
        items.put("retailMarkupRange", new Input(this, "retailMarkupRange"));
        items.put("retailMarkupMin", new Input(this, "retailMarkupMin"));
        items.put("retailMarkupMax", new Input(this, "retailMarkupMax"));
        items.put("retailMarkup", new Input(this, "retailMarkup"));
        items.put("retailPriceRange", new Input(this, "retailPriceRange"));
        items.put("retailPriceMin", new Input(this, "retailPriceMin"));
        items.put("retailPriceMax", new Input(this, "retailPriceMax"));
        items.put("retailPrice", new Input(this, "retailPrice"));
        items.put("retailMarkupHint", new Input(this, "retailMarkupHint"));
        items.put("retailPriceHint", new Input(this, "retailPriceHint"));
        items.put("group", new NonType(this, "group"));
        items.put("category", new NonType(this, "category"));
        items.put("subCategory", new NonType(this, "subCategory"));
        items.put("rounding", new SelectByVisibleText(this, "rounding"));
        items.put("rounding price", new NonType(this, By.xpath("//*[@class='productForm__rounding']")));
    }

    public void fieldInput(ExamplesTable fieldInputTable) {
        for (Map<String, String> row : fieldInputTable.getRows()) {
            String elementName = row.get("elementName");
            String inputText = row.get("value");
            input(elementName, inputText);
        }
    }

    public void selectByValue(String elementName, String value) {
        items.get(elementName).setValue(value);
    }

    public void createButtonClick() {
        new ButtonFacade(getDriver()).click();
        new PreLoader(getDriver()).await();
    }

    public void checkDropDownDefaultValue(String dropDownType, String expectedValue) {
        WebElement element = items.get(dropDownType).getWebElement();
        String selectedValue = $(element).getSelectedValue();
        assertEquals(
                String.format("The default value for '%s' dropDown is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue),
                selectedValue, expectedValue
        );
    }

    public void checkFieldLength(String elementName, int fieldLength) {
        CommonItem item = items.get(elementName);
        commonPage.checkFieldLength(elementName, fieldLength, item.getWebElement());
    }

    public void elementClick(String elementName) {
        items.get(elementName).click();
    }

    public void checkElementPresence(String elementName, String action) {
        switch (action) {
            case "is":
                $(items.get(elementName).getWebElement()).shouldBeVisible();
                break;
            case "is not":
                $(items.get(elementName).getWebElement()).shouldNotBeVisible();
                break;
            default:
                fail(CommonPage.ERROR_MESSAGE);
        }
    }

    public void retailPriceHintClick() {
        By retailPriceHintFindBy = items.get("retailPriceHint").getFindBy();
        By retailMarkupHintFindBy = items.get("retailMarkupHint").getFindBy();
        if (isElementVisible(retailPriceHintFindBy) && !isElementVisible(retailMarkupHintFindBy)) {
            findVisibleElement(retailPriceHintFindBy).click();
        }
    }

    public void roundingPreloaderSpinnerWait() {
        //TODO preloader common object
        waiter.waitUntilIsNotVisible(
                By.xpath("//*[@class='productForm__rounding preloader_spinner']")
        );
    }

    public void checkElementIsDisabled(String elementName) {
        assertNotNull("The disabled attribute is not present in the element", items.get(elementName).getWebElement().getAttribute("disabled"));
    }

    public void checkDropDownDefaultValue(String expectedValue) {
        WebElement element = items.get("rounding").getVisibleWebElement();
        commonPage.checkDropDownDefaultValue(element, expectedValue);
    }
}
