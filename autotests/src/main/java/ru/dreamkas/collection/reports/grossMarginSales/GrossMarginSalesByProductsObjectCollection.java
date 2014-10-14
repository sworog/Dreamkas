package ru.dreamkas.collection.reports.grossMarginSales;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;


public class GrossMarginSalesByProductsObjectCollection extends AbstractObjectCollection {
    public GrossMarginSalesByProductsObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new GrossMarginSalesByProductsObject(element);
    }
}