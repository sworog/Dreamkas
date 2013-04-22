package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;

public class Date extends DateTime {

    public Date(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    public Date(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void dateTimePickerClose() {
    }
}
