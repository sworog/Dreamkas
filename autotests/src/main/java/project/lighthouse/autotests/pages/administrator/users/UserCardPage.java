package project.lighthouse.autotests.pages.administrator.users;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.NonType;

public class UserCardPage extends CommonPageObject {

    public UserCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("name", new NonType(this, "name"));
        items.put("position", new NonType(this, "position"));
        items.put("username", new NonType(this, "username"));
        items.put("password", new NonType(this, "password"));
        items.put("role", new NonType(this, "role"));
    }

    public void checkCardValue(String elementName, String expectedValue) {
        commonActions.checkElementValue("", elementName, expectedValue);
    }

    public void editButtonClick() {
        String editButtonXpath = "//*[@class='user__editLink']";
        commonActions.elementClick(By.xpath(editButtonXpath));
    }

    public void pageBackLink() {
        findVisibleElement(By.className("page__backLink")).click();
    }
}
