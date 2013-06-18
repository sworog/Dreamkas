package project.lighthouse.autotests.pages.users;

import net.thucydides.core.pages.PageObject;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

import java.io.IOException;

public class UserApi extends PageObject {

    ApiConnect apiConnect = new ApiConnect(getDriver());

    public UserApi(WebDriver driver) {
        super(driver);
    }

    public void createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        apiConnect.createUserThroughPost(name, position, login, password, role);
    }

    public void navigateToTheUserPage(String login) throws JSONException {
        apiConnect.navigateToTheUserPage(login);
    }
}
