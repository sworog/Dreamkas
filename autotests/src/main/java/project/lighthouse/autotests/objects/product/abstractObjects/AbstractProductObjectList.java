package project.lighthouse.autotests.objects.product.abstractObjects;

import org.openqa.selenium.WebElement;

import java.util.Map;

abstract public class AbstractProductObjectList {

    public WebElement element;

    public AbstractProductObjectList(WebElement element) {
        this.element = element;
    }

    abstract public String getValues();

    abstract public void setProperties();

    public void click() {
        element.click();
    }

    abstract public Boolean rowIsEqual(Map<String, String> row);
}
