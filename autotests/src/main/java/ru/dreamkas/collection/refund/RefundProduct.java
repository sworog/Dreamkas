package ru.dreamkas.collection.refund;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public class RefundProduct extends AbstractObject implements ObjectLocatable, ResultComparable {

    private String name;
    private String barcode;

    public RefundProduct(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("productName")).getText();
        barcode = getElement().findElement(By.name("productBarcode")).getText();
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("barcode", barcode, row.get("barcode"));
    }

    public void setQuantity(String quantity) {
        WebElement quantityInput = getElement().findElement(By.name("quantity"));
        quantityInput.clear();
        quantityInput.sendKeys(quantity);
    }

    public void clickOnPlusButton() {
        getElement().findElement(By.xpath(".//*[contains(@class, 'countUp')]")).click();
    }

    public void clickOnMinusButton() {
        getElement().findElement(By.xpath(".//*[contains(@class, 'countDown')]")).click();
    }

    public String getQuantity() {
        return getElement().findElement(By.name("quantity")).getAttribute("value");
    }
}
