package project.lighthouse.autotests.pages.elements;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;

public class Date extends DateTime {

    public Date(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public Date(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void dateTimePickerClose() {
        String xpath = "//*[@class='datepicker datepicker_noTime']//*[@class='button button_color_blue datepicker__saveLink']";
        pageObject.findBy(xpath).click();
    }
}
