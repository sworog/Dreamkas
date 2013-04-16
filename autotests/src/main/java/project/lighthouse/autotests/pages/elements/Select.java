package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;

public class Select extends CommonItem {

    public Select(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Select(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        $().selectByValue(value);
    }
}
