package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;

public class Autocomplete extends CommonItem {

    public static final String AUTOCOMPLETE_XPATH_PATTERN = "//*[@role='presentation']/*[text()='%s']";

    public Autocomplete(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Autocomplete(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        if (value.startsWith("!")) {
            value = value.substring(1);
            $().type(value);
        } else {
            $().type(value);
            String xpath = String.format(AUTOCOMPLETE_XPATH_PATTERN, value);
            try {
                pageObject.findBy(xpath).click();
            } catch (Exception e) {
                e.printStackTrace();
                String errorMessage = String.format("Can't find '%s' value in autoComplete results", value);
                throw new AssertionError(errorMessage);
            }
        }
    }


}
