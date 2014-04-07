package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;

@DefaultUrl("/")
public class AuthorizationPage extends CommonPageObject {

    @FindBy(id = "form_login")
    @SuppressWarnings("unused")
    private WebElement loginFormWebElement;

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

    public WebElement getLoginFormWebElement() {
        return findVisibleElement(loginFormWebElement);
    }
}
