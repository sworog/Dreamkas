package ru.dreamkas.pageObjects.elements;

import net.thucydides.core.annotations.findby.By;
import net.thucydides.core.pages.PageObject;

import org.openqa.selenium.WebElement;

import java.util.List;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Settable;

public class Spinner extends Collection implements Settable {

    public Spinner(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    @Override
    public void set(String value) {
        WebElement spinner = getElement();
        spinner.click();

        clickOnElementWithText(getItems(), value);
    }

    @Override
    public List<WebElement> getItems() {
        return getCommonPageObject().getAppiumDriver().findElements(By.id("android:id/text1"));
    }
}
