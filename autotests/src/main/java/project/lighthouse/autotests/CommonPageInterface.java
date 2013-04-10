package project.lighthouse.autotests;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.pages.common.CommonItem;

public interface CommonPageInterface {

    void isRequiredPageOpen(String pageObjectName);
    String generateTestData(int n);
    boolean isPresent(String xpath);
    void checkCreateAlertSuccess(String name);
    void checkFieldLength(String elementName, int fieldLength, WebElement element);
    void setValue(CommonItem item, String value);
    void shouldContainsText(String elementName, WebElement element, String expectedValue);
}
