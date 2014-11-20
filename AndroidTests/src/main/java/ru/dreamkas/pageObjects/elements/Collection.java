package ru.dreamkas.pageObjects.elements;

import net.thucydides.core.annotations.findby.By;

import org.openqa.selenium.WebElement;

import java.util.List;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Elementable;
import ru.dreamkas.pageObjects.elements.interfaces.Settable;

public abstract class Collection extends ViewElement {

    public Collection(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    public Collection(CommonPageObject commonPageObject, String customPackage, String id) {
        super(commonPageObject, customPackage, id);
    }

    public void clickOnElementWithText(List<WebElement> webElements, String text) {
        for (WebElement webElement : webElements) {
            if (webElement.getText().equals(text)) {
                webElement.click();
                break;
            }
        }
    }

    public abstract <T> List<T> getItems();

    public void click(String item) {
        List<WebElement> textViews = getElement().findElements(By.className("android.widget.TextView"));
        clickOnElementWithText(textViews, item);
    }
}
