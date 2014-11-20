package ru.dreamkas.pageObjects.elements;

import net.thucydides.core.annotations.findby.By;

import org.openqa.selenium.WebElement;

import java.util.ArrayList;
import java.util.List;

import ru.dreamkas.pageObjects.CommonPageObject;

public class SearchResultListView extends Collection {

    public SearchResultListView(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    public SearchResultListView(CommonPageObject commonPageObject, String customPackage, String id) {
        super(commonPageObject, customPackage, id);
    }

    @Override
    public List<String> getItems() {
        List<WebElement> items = getElement().findElements(By.className("android.widget.TextView"));

        List<String> strs = new ArrayList<String>();
        for (WebElement webElement : items) {
            strs.add(webElement.getText());
        }

        return strs;
    }


}
