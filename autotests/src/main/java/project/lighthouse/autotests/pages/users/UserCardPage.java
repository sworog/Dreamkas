package project.lighthouse.autotests.pages.users;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.elements.NonType;

public class UserCardPage extends UserCreatePage {

    public UserCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("name", new NonType(this, "name"));
        items.put("position", new NonType(this, "position"));
        items.put("login", new NonType(this, "login"));
        items.put("password", new NonType(this, "password"));
        items.put("role", new NonType(this, "role"));
    }

    public void checkCardValue(String elementName, String expectedValue) {
        commonActions.checkElementValue("", elementName, expectedValue);
    }

    public void checkCardValue(ExamplesTable checkValuesTable) {
        commonActions.checkElementValue("", checkValuesTable);
    }

    public void editButtonClick() {
        String editButtonXpath = "";
        findElement(By.xpath(editButtonXpath)).click();
    }
}
