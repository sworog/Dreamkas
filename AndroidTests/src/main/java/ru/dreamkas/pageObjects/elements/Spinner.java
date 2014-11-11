package ru.dreamkas.pageObjects.elements;

import net.thucydides.core.annotations.findby.By;

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
        getCommonPageObject().waitForRenderedElements(getFindBy());
        WebElement spinner = getCommonPageObject().getAppiumDriver().findElement(getFindBy());
        spinner.click();

        List<WebElement> items = getCommonPageObject().getAppiumDriver().findElements(By.id("android:id/text1"));
        clickOnElementWithText(items, value);
    }
}
