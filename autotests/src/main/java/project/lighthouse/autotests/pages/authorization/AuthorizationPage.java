package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;

@DefaultUrl("/")
public class AuthorizationPage extends CommonPageObject {

    @FindBy(id = "form_login")
    @SuppressWarnings("unused")
    private WebElement loginFormWebElement;

    @FindBy(xpath = "//*[@class='navigationBar__userName']")
    @SuppressWarnings("unused")
    private WebElement userNameWebElement;

    public AuthorizationPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("userName", new Input(this, "username"));
        put("password", new Input(this, "password"));
    }

    public void loginButtonClick() {
        new ButtonFacade(this, "Войти").click();
    }

    //TODO move to MenuNavigation page object class
    public void logOutButtonClick() {
        findVisibleElement(userNameWebElement).click();
        new ButtonFacade(this, "Выйти").click();
    }

    //TODO move to MenuNavigation page object class
    public String getUserNameText() {
        return findVisibleElement(userNameWebElement).getText();
    }

    public WebElement getLoginFormWebElement() {
        return findVisibleElement(loginFormWebElement);
    }
}
