package project.lighthouse.autotests.pages.user;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;

public class UserEditPage extends CommonPageObject {

    public UserEditPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("email", new Input(this, "email"));
        put("name", new Input(this, "name"));
        put("password", new Input(this, "password"));
    }

    public void saveButtonClick() {
        new ButtonFacade(this, "Сохранить").click();
    }
}
