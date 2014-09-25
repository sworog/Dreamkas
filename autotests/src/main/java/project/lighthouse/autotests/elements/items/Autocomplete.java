package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.item.CommonItem;
import project.lighthouse.autotests.common.objects.CommonPageObject;

import static junit.framework.Assert.fail;

public class Autocomplete extends CommonItem {

    public static final String AUTOCOMPLETE_XPATH_PATTERN = "//*[@role='presentation']/*[text()='%s']";

    public Autocomplete(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Autocomplete(CommonPageObject pageObject, String name, String label) {
        super(pageObject, name, label);
    }

    @Override
    public void setValue(String value) {
        if (value.startsWith("!")) {
            value = value.substring(1);
            getVisibleWebElementFacade().type(value);
        } else {
            getVisibleWebElementFacade().type(value);
            String xpath = String.format(AUTOCOMPLETE_XPATH_PATTERN, value);
            try {
                getPageObject().findVisibleElement(By.xpath(xpath)).click();
            } catch (Exception e) {
                fail(
                        String.format("Can't find '%s' value in autoComplete results", value)
                );
            }
        }
    }


}
