package ru.dreamkas.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.ButtonFacade;
import ru.dreamkas.elements.Buttons.LinkFacade;
import ru.dreamkas.elements.bootstrap.buttons.SuccessBtnFacade;
import ru.dreamkas.elements.items.Input;

@DefaultUrl("/signup")
public class SignUpPage extends CommonPageObject {

    public SignUpPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("email", new Input(this, "email"));
        put("signUpButton", new SuccessBtnFacade(this, By.xpath("//*[contains(@class, 'btn btn-success') and contains(text(), 'Зарегистрироваться')]")));
    }
}
