package ru.dreamkas.pageObjects.elements;

import net.thucydides.core.annotations.findby.By;

import org.openqa.selenium.WebElement;

import java.util.ArrayList;
import java.util.List;

import ru.dreamkas.pageObjects.CommonPageObject;

public class ReceiptList extends Collection {

    public ReceiptList(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    public ReceiptList(CommonPageObject commonPageObject, String customPackage, String id) {
        super(commonPageObject, customPackage, id);
    }

    @Override
    public List<List<String>> getItems() {
        List<WebElement> items = getElement().findElements(By.className("android.widget.LinearLayout"));

        List<List<String>> rows = new ArrayList<List<String>>();
        for (WebElement webElement : items) {
            List<String> cells = new ArrayList<String>();
            List<WebElement> elements = webElement.findElements(By.className("android.widget.TextView"));
            for(WebElement element : elements){
                cells.add(element.getText());
            }
            rows.add(cells);
        }
        return rows;
    }




}
