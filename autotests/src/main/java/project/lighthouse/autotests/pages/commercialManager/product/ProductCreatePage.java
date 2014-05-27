package project.lighthouse.autotests.pages.commercialManager.product;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.*;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

@DefaultUrl("/products/create")
public class ProductCreatePage extends CommonPageObject {

    public ProductCreatePage(WebDriver driver) {
        super(driver);
    }

    public void createElements() {
        put("type", new SelectByLabel(this, By.name("type"), By.className("productForm__productTypeRadio")));
        put("sku", new Input(this, "sku"));
        put("name", new Input(this, "name"));
        put("purchasePrice", new Input(this, "purchasePrice"));
        put("vat", new SelectByValue(this, "vat"));
        put("barcode", new Input(this, "barcode"));
        put("vendor", new Input(this, "vendor"));
        put("vendorCountry", new Input(this, "vendorCountry"));
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
        put("units", new NonType(this, "units"));

        put("nameOnScales", new Input(this, "typeProperties.nameOnScales"));
        put("descriptionOnScales", new Input(this, "typeProperties.descriptionOnScales"));
        put("ingredients", new Input(this, "typeProperties.ingredients"));
        put("nutritionFacts", new Input(this, "typeProperties.nutritionFacts"));
        put("shelfLife", new Input(this, "typeProperties.shelfLife"));

        put("alcoholByVolume", new Input(this, "typeProperties.alcoholByVolume"));
        put("volume", new Input(this, "typeProperties.volume"));

        put("inventory", new NonType(this, By.xpath("//*[@model-attribute='inventoryElement']")));
        put("averageDailySales", new NonType(this, By.xpath("//*[@model-attribute='averageDailySalesElement']")));
        put("inventoryDays", new NonType(this, By.xpath("//*[@model-attribute='inventoryDaysElement']")));
    }

    public void createButtonClick() {
        new ButtonFacade(this).click();
        new PreLoader(getDriver()).await();
    }

    public void retailPriceHintClick() {
        By retailPriceHintFindBy = getItems().get("retailPriceHint").getFindBy();
        By retailMarkupHintFindBy = getItems().get("retailMarkupHint").getFindBy();
        if (getWaiter().isElementVisible(retailPriceHintFindBy) && !getWaiter().isElementVisible(retailMarkupHintFindBy)) {
            findVisibleElement(retailPriceHintFindBy).click();
        }
    }
}
