package project.lighthouse.autotests.pages.user;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.NonType;

public class UserCardPage extends CommonPageObject {

    @FindBy(className = "user__editLink")
    @SuppressWarnings("unused")
    private WebElement editButtonLinkWEbWebElement;

    @FindBy(className = "page__backLink")
    @SuppressWarnings("unused")
    private WebElement pageBackLinkWebElement;

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
        findVisibleElement(editButtonLinkWEbWebElement).click();
    }

    public void pageBackLinkClick() {
        findVisibleElement(pageBackLinkWebElement).click();
    }

    public void logOutButtonClick() {
        new ButtonFacade(this, "Выйти").click();
    }
}
