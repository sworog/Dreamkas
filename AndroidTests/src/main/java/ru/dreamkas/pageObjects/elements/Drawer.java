package ru.dreamkas.pageObjects.elements;

import net.thucydides.core.annotations.findby.By;

import org.openqa.selenium.WebElement;

import java.util.List;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Settable;

public class Drawer extends Collection implements Settable {

    public Drawer(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    public Drawer(CommonPageObject commonPageObject, String customPackage, String id) {
        super(commonPageObject, customPackage, id);
    }

    @Override
    public void set(String value) {
        WebElement drawer = getCommonPageObject().getAppiumDriver().findElement(getFindBy());
        drawer.click();
        clickOnElementWithText(getCommonPageObject().getAppiumDriver().findElements(By.xpath("//android.widget.TextView")), value);
    }
}
