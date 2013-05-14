package project.lighthouse.autotests.pages.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.pages.elements.Input;
import project.lighthouse.autotests.pages.elements.Select;
import project.lighthouse.autotests.pages.elements.Textarea;

import java.util.Map;

@DefaultUrl("/product/create")
public class ProductCreatePage extends CommonPageObject {

    public static final String PRODUCT_NAME = "product";
    private static final String INPUT_XPATH_PATTERN = "//*[@class='form productForm']//*[@name='%s']";
    private static final String CARD_VIEW_XPATH_PATTERN = "//*[@name='product']//*[@name='%s']";

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
        items.put("retailMarkup", new Input(this, "retailMarkup"));
        items.put("retailPrice", new Input(this, "retailPrice"));
        items.put("retailMarkupHint", new Input(this, "retailMarkupHint"));
        items.put("retailPriceHint", new Input(this, "retailPriceHint"));

        /*items.put("sku", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "sku"))));
        items.put("name", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "name"))));
        items.put("unit", new Select(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "units"))));
        items.put("purchasePrice", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "purchasePrice"))));
        items.put("vat", new Select(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "vat"))));
        items.put("barcode", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "barcode"))));
        items.put("vendor", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "vendor"))));
        items.put("vendorCountry", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "vendorCountry"))));
        items.put("info", new Textarea(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "info"))));
        items.put("retailMarkup", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "retailMarkup"))));
        items.put("retailPrice", new Input(this, By.xpath(String.format(INPUT_XPATH_PATTERN, "retailPrice"))));

        *//*Card view*//*
        items.put("cardViewSku", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "sku"))));
        items.put("cardViewName", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "name"))));
        items.put("cardViewUnit", new Select(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "units"))));
        items.put("cardViewPurchasePrice", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "purchasePrice"))));
        items.put("cardViewVat", new Select(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "vat"))));
        items.put("cardViewBarcode", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "barcode"))));
        items.put("cardViewVendor", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "vendor"))));
        items.put("cardViewVendorCountry", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "vendorCountry"))));
        items.put("cardViewInfo", new Textarea(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "info"))));
        items.put("cardViewRetailMarkup", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "retailMarkup"))));
        items.put("cardViewRetailPrice", new Input(this, By.xpath(String.format(CARD_VIEW_XPATH_PATTERN, "retailPrice"))));*/
    }

    public void fieldInput(ExamplesTable fieldInputTable) {
        for (Map<String, String> row : fieldInputTable.getRows()) {
            String elementName = row.get("elementName");
            String inputText = row.get("inputText");
            input(elementName, inputText);
        }
    }

    public void selectByValue(String elementName, String value) {
        items.get(elementName).setValue(value);
    }

    public void createButtonClick() {
        findBy("//*[@class='button button_color_blue']/input").click();
        waiter.waitUntilIsNotVisible(By.xpath("//*[@class='button button_color_blue preloader']"));
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
        commonPage.checkFieldLength(elementName, fieldLength, item.getWebElement());
    }

    public void elementClick(String elementName) {
        items.get(elementName).getWebElement().click();
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
                throw new AssertionError(CommonPage.ERROR_MESSAGE);
        }
    }
}
