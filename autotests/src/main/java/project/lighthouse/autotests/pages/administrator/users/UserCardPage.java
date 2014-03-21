package project.lighthouse.autotests.pages.administrator.users;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.NonType;

public class UserCardPage extends CommonPageObject {

    public UserCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new NonType(this, "name"));
        put("position", new NonType(this, "position"));
        put("username", new NonType(this, "username"));
        put("password", new NonType(this, "password"));
        put("role", new NonType(this, "role"));
    }

    public void editButtonClick() {
        String editButtonXpath = "//*[@class='user__editLink']";
        click(By.xpath(editButtonXpath));
    }

    public void pageBackLink() {
        findVisibleElement(By.className("page__backLink")).click();
    }

    public void logOutButtonClick() {
        new ButtonFacade(this, "Выйти").click();
    }
}
