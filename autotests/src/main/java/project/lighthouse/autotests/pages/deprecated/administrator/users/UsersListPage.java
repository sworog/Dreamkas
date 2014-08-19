package project.lighthouse.autotests.pages.deprecated.administrator.users;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.NonType;

@Deprecated
@DefaultUrl("/users")
public class UsersListPage extends CommonPageObject {

    public UsersListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new NonType(this, "name"));
        put("position", new NonType(this, "position"));
        put("username", new NonType(this, "username"));
        put("password", new NonType(this, "password"));
        put("role", new NonType(this, "role"));
    }

    public void createNewUserButtonClick() {
        new ButtonFacade(this, "Добавить пользователя").click();
    }
}
