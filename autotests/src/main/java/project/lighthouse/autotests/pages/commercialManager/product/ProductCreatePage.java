package project.lighthouse.autotests.pages.commercialManager.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.*;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

import static junit.framework.Assert.*;

@DefaultUrl("/products/create")
public class ProductCreatePage extends CommonPageObject {

    public ProductCreatePage(WebDriver driver) {
        super(driver);
    }

    public void createElements() {
        put("type", new SelectByLabel(this, By.className("productForm__productTypeRadio")));
        put("sku", new Input(this, "sku"));
        put("name", new Input(this, "name"));
        put("purchasePrice", new Input(this, "purchasePrice"));
        put("vat", new SelectByValue(this, "vat"));
        put("barcode", new Input(this, "barcode"));
        put("vendor", new Input(this, "vendor"));
        put("vendorCountry", new Input(this, "vendorCountry"));
        put("info", new Textarea(this, "info"));
        put("retailMarkupRange", new Input(this, "retailMarkupRange"));
        put("retailMarkupMin", new Input(this, "retailMarkupMin"));
        put("retailMarkupMax", new Input(this, "retailMarkupMax"));
        put("retailMarkup", new Input(this, "retailMarkup"));
        put("retailPriceRange", new Input(this, "retailPriceRange"));
        put("retailPriceMin", new Input(this, "retailPriceMin"));
        put("retailPriceMax", new Input(this, "retailPriceMax"));
        put("retailPrice", new Input(this, "retailPrice"));
        put("retailMarkupHint", new Input(this, "retailMarkupHint"));
        put("retailPriceHint", new Input(this, "retailPriceHint"));
        put("group", new NonType(this, "group"));
        put("category", new NonType(this, "category"));
        put("subCategory", new NonType(this, "subCategory"));
        put("rounding", new SelectByVisibleText(this, "rounding.name"));
        put("rounding price", new NonType(this, By.xpath("//*[@class='productForm__rounding']")));

        put("nameOnScales", new Input(this, "typeProperties.nameOnScales"));
        put("descriptionOnScales", new Input(this, "typeProperties.descriptionOnScales"));
        put("ingredients", new Input(this, "typeProperties.ingredients"));
        put("nutritionFacts", new Input(this, "typeProperties.nutritionFacts"));
        put("shelfLife", new Input(this, "typeProperties.shelfLife"));

        put("inventory", new NonType(this, By.xpath("//*[@model-attribute='inventoryElement']")));
        put("averageDailySales", new NonType(this, By.xpath("//*[@model-attribute='averageDailySalesElement']")));
        put("inventoryDays", new NonType(this, By.xpath("//*[@model-attribute='inventoryDaysElement']")));
    }

    public void createButtonClick() {
        new ButtonFacade(this).click();
        new PreLoader(getDriver()).await();
    }

    public void checkDropDownDefaultValue(String dropDownType, String expectedValue) {
        WebElement element = getItems().get(dropDownType).getWebElement();
        String selectedValue = $(element).getSelectedValue();
        assertEquals(
                String.format("The default value for '%s' dropDown is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue),
                selectedValue, expectedValue
        );
    }

    public void checkElementPresence(String elementName, String action) {
        switch (action) {
            case "is":
                $(getItems().get(elementName).getWebElement()).shouldBeVisible();
                break;
            case "is not":
                $(getItems().get(elementName).getWebElement()).shouldNotBeVisible();
                break;
            default:
                fail(CommonPage.ERROR_MESSAGE);
        }
    }

    public void retailPriceHintClick() {
        By retailPriceHintFindBy = getItems().get("retailPriceHint").getFindBy();
        By retailMarkupHintFindBy = getItems().get("retailMarkupHint").getFindBy();
        if (getWaiter().isElementVisible(retailPriceHintFindBy) && !getWaiter().isElementVisible(retailMarkupHintFindBy)) {
            findVisibleElement(retailPriceHintFindBy).click();
        }
    }

    public void checkElementIsDisabled(String elementName) {
        assertNotNull("The disabled attribute is not present in the element", getItems().get(elementName).getWebElement().getAttribute("disabled"));
    }

    public void checkDropDownDefaultValue(String expectedValue) {
        WebElement element = getItems().get("rounding").getVisibleWebElement();
        getCommonActions().checkDropDownDefaultValue(element, expectedValue);
    }
}
