package ru.dreamkas.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.LinkFacade;
import ru.dreamkas.elements.bootstrap.buttons.SuccessBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;

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
        put("loginButton", new SuccessBtnFacade(this, By.xpath("//*[contains(@class, 'btn btn-success') and contains(text(), 'Войти')]")));
        put("forgotPasswordLink", new LinkFacade(this, "Забыли пароль?"));
    }
}
