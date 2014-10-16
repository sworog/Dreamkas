package ru.dreamkas.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.ButtonFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;

@DefaultUrl("/restorePassword")
public class RestorePasswordPage extends CommonPageObject {

    public RestorePasswordPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("email", new Input(this, "email"));
        put("recoverPasswordButton", new ButtonFacade(this, "Восcтановить"));
        put("pageTitleText", new NonType(this, By.xpath("//*[@class='content']/h1")));
        put("pageText", new NonType(this, By.xpath("//*[@class='content']/p")));
    }
}
