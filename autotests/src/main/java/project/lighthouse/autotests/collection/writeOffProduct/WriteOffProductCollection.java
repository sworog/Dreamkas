package project.lighthouse.autotests.collection.writeOffProduct;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

public class WriteOffProductCollection extends AbstractObjectCollection {

    public WriteOffProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("writeOffProduct"));
    }

    @Override
    public WriteOffProductObject createNode(WebElement element) {
        return new WriteOffProductObject(element);
    }
}
