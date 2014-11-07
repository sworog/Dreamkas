package ru.dreamkas.common.item.interfaces;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;

public interface Findable extends CommonItemType {

    public By getFindBy();

    public WebElement getVisibleWebElement();

    public WebElementFacade getVisibleWebElementFacade();
}
