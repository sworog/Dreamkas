package ru.dreamkas.collection.posAutoComplete;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public class PosAutoCompeteResult extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String name;
    private String sku;
    private String barcode;

    public PosAutoCompeteResult(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        sku = getElement().findElement(By.name("sku")).getText();
        barcode = getElement().findElement(By.name("barcode")).getText();
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("sku", sku, row.get("sku"))
                .compare("barcode", barcode, row.get("barcode"));
    }
}
