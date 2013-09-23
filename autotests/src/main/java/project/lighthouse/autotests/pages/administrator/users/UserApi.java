package project.lighthouse.autotests.pages.administrator.users;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.User;
import project.lighthouse.autotests.pages.administrator.api.AdministratorApi;

import java.io.IOException;

public class UserApi extends AdministratorApi {

    public UserApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public User createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        return apiConnect.createUserThroughPost(name, position, login, password, role);
    }

    public void navigateToTheUserPage(String userName) throws JSONException {
        String userPageUrl = apiConnect.getUserPageUrl(userName);
        getDriver().navigate().to(userPageUrl);
    }

    public User getUser(String userName) throws IOException, JSONException {
        if (!StaticData.users.containsKey(userName)) {
            return apiConnect.getUser(userName);
        } else {
            return StaticData.users.get(userName);
        }
    }
}
