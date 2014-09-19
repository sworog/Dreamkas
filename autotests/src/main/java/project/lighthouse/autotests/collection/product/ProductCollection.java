package project.lighthouse.autotests.collection.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

public class ProductCollection extends AbstractObjectCollection {

    public ProductCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public ProductObject createNode(WebElement element) {
        return new ProductObject(element);
    }
}
