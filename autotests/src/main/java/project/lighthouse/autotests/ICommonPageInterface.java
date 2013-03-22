package project.lighthouse.autotests;

import org.openqa.selenium.WebElement;

public interface ICommonPageInterface {

    void isRequiredPageOpen(String pageObjectName);
    String GenerateTestData(int n);
    boolean IsPresent(String xpath);
    void CheckCreateAlertSuccess(String name);
    void CheckFieldLength(String elementName, int fieldLength, WebElement element);
}
