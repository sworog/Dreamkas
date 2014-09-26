package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.objects.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.SuccessBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;

@DefaultUrl("/")
public class AuthorizationPage extends CommonPageObject {

    public AuthorizationPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("userName", new Input(this, "username"));
        put("password", new Input(this, "password"));
        put("email", new Input(this, "username"));
        put("signUpPageTitle", new NonType(this, By.xpath("//*[@class='content']/p")));
        put("form_login", new NonType(this, By.id("form_login")));
    }

    public void loginButtonClick() {
        new SuccessBtnFacade(this, By.xpath("//*[contains(@class, 'btn btn-success') and contains(text(), 'Войти')]")).click();
    }

    public void forgotPasswordLinkClick() {
        new LinkFacade(this, "Забыли пароль?").click();
    }
}
