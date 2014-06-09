package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;

@DefaultUrl("/restorePassword")
public class RestorePasswordPage extends CommonPageObject {

    public RestorePasswordPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("email", new Input(this, "email"));
    }

    public void recoverPasswordButtonClick() {
        new ButtonFacade(this, "Восcтановить").click();
    }

    public String getPageTitleText() {
        return findVisibleElement(By.xpath("//*[@class='content']/h1")).getText();
    }

    public String getPageText() {
        return findVisibleElement(By.xpath("//*[@class='content']/p")).getText();
    }
}
