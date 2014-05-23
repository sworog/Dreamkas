package project.lighthouse.autotests.objects.web.product.barcodes;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Class representing barcode object
 */
public class BarcodeObject extends AbstractObject implements ObjectLocatable, ResultComparable {

    private WebElement barcode;
    private WebElement quantity;
    private WebElement price;

    public BarcodeObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        barcode = getElement().findElement(By.xpath(".//*[contains(@name, 'barcode')]"));
        quantity = getElement().findElement(By.xpath(".//*[contains(@name, 'quantity')]"));
        price = getElement().findElement(By.xpath(".//*[contains(@name, 'price')]"));
    }

    @Override
    public String getObjectLocator() {
        return getTextValue(barcode);
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("barcode", getTextValue(barcode), row.get("barcode"))
                .compare("quantity", getTextValue(quantity), row.get("quantity"))
                .compare("price", getTextValue(price), row.get("price"));
    }

    public void barcodeFieldType(String value) {
        barcode.clear();
        barcode.sendKeys(value);
    }

    public void quantityFieldType(String value) {
        quantity.clear();
        quantity.sendKeys(value);
    }

    public void priceFieldType(String value) {
        price.clear();
        price.sendKeys(value);
    }

    public void deleteButtonClick() {
        new Actions(getWebDriver())
                .moveToElement(getElement())
                .click(getElement().findElement(org.openqa.selenium.By.xpath(".//*[text()='Удалить']"))).build().perform();
    }

    private String getTextValue(WebElement element) {
        return element.getAttribute("value");
    }
}
