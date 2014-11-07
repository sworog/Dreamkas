package ru.dreamkas.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.buttons.SuccessBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;

@DefaultUrl("/recover")
public class RestorePasswordPage extends CommonPageObject {

    public RestorePasswordPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("email", new Input(this, "email"));
        put("recoverPasswordButton", new SuccessBtnFacade(this, org.openqa.selenium.By.xpath("//*[contains(@class, 'btn btn-success') and contains(text(), 'Восстановить')]")));
        put("pageTitleText", new NonType(this, By.xpath("//*[@class='form__header']")));
    }
}
