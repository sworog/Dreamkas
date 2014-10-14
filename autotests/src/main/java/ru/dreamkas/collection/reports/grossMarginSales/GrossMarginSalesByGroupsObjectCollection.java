package ru.dreamkas.collection.reports.grossMarginSales;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.catalog.GroupObject;

/**
 * Created by d.raslov on 14.10.2014.
 */
public class GrossMarginSalesByGroupsObjectCollection extends AbstractObjectCollection {
    public GrossMarginSalesByGroupsObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new GrossMarginSalesByGroupsObject(element);
    }
}
