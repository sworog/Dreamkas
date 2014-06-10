package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.items.Input;

@DefaultUrl("/")
public class AuthorizationPage extends CommonPageObject {

    @FindBy(id = "form_login")
    @SuppressWarnings("unused")
    private WebElement loginFormWebElement;

    @FindBy(xpath = "//*[@class='content']/p")
    WebElement signUpPageTitleWebElement;

    public AuthorizationPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("email", new Input(this, "email"));
        put("password", new Input(this, "password"));
    }

    public void loginButtonClick() {
        new ButtonFacade(this, "Войти").click();
    }

    public WebElement getLoginFormWebElement() {
        return findVisibleElement(loginFormWebElement);
    }

    public String getSignUpPageTitleText() {
        return findVisibleElement(signUpPageTitleWebElement).getText();
    }

    public void forgotPasswordLinkClick() {
        new LinkFacade(this, "Забыли пароль?").click();
    }
}
