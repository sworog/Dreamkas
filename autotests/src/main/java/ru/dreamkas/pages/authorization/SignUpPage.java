package ru.dreamkas.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.ButtonFacade;
import ru.dreamkas.elements.items.Input;

@DefaultUrl("/signup")
public class SignUpPage extends CommonPageObject {

    public SignUpPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("email", new Input(this, "email"));
        put("signUpButton", new ButtonFacade(this, "Зарегистрироваться"));
    }
}
