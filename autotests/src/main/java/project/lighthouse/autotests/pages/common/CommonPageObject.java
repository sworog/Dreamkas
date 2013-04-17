package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;

import java.util.HashMap;
import java.util.Map;

abstract public class CommonPageObject extends PageObject {

    protected CommonPage commonPage = new CommonPage(getDriver());

    public Map<String, CommonItem> items = new HashMap();

    public CommonPageObject(WebDriver driver) {
        super(driver);
        createElements();
    }

    abstract public void createElements();

    public void input(String elementName, String inputText) {
        items.get(elementName).setValue(inputText);
    }
}
