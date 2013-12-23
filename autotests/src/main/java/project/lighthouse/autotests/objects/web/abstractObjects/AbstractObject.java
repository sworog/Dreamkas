package project.lighthouse.autotests.objects.web.abstractObjects;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;

import java.util.HashMap;
import java.util.Map;

abstract public class AbstractObject {

    private WebElement element;
    private WebDriver webDriver;
    public Map<String, ObjectProperty> objectPropertyMap = new HashMap<>();

    public AbstractObject(WebElement element) {
        this.element = element;
        setProperties();
    }

    public AbstractObject(WebElement element, WebDriver webDriver) {
        this.element = element;
        this.webDriver = webDriver;
        setProperties();
    }

    public WebDriver getWebDriver() {
        return webDriver;
    }

    public WebElement getElement() {
        return element;
    }

    abstract public void setProperties();

    public String setProperty(By findBy) {
        Waiter waiter = new Waiter(webDriver, 0);
        if (!waiter.invisibilityOfElementLocated(getElement(), findBy)) {
            return getElement().findElement(findBy).getText();
        } else {
            return "";
        }
    }

    public ObjectProperty getObjectProperty(String propertyName) {
        return objectPropertyMap.get(propertyName);
    }

    public ObjectProperty setObjectProperty(String propertyName, By propertyFindBy) {
        objectPropertyMap.put(propertyName, new ObjectProperty(element, propertyFindBy));
        return getObjectProperty(propertyName);
    }
}
