package ru.dreamkas.collection.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;

public class ProductCollection extends AbstractObjectCollection {

    public ProductCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public ProductObject createNode(WebElement element) {
        return new ProductObject(element);
    }
}
