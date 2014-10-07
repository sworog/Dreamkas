package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.pageObjects.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;

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
