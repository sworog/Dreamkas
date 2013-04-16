package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPage;

public class NonType extends CommonItem {

    public NonType(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public NonType(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        String errorMessage = String.format(CommonPage.ERROR_MESSAGE, "NonType");
        throw new AssertionError(errorMessage);
    }
}
