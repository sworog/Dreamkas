package project.lighthouse.autotests.steps.api.administrator;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.ApiConnect;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.User;
import project.lighthouse.autotests.pages.administrator.users.UserCreatePage;

import java.io.IOException;

public class UserApiSteps extends ScenarioSteps {

    private ApiConnect apiConnect;

    UserCreatePage userCreatePage;

    public UserApiSteps() throws JSONException {
        apiConnect = new ApiConnect("administrator", "lighthouse");
    }

    @Step
    public User createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        String updatedRole = userCreatePage.replaceSelectedValue(role);
        return apiConnect.createUserThroughPost(name, position, login, password, updatedRole);
    }

    @Step
    public void navigateToTheUserPage(String userName) throws JSONException {
        String userPageUrl = apiConnect.getUserPageUrl(userName);
        getDriver().navigate().to(userPageUrl);
    }

    @Step
    public User getUser(String userName) throws IOException, JSONException {
        if (!StaticData.users.containsKey(userName)) {
            return apiConnect.getUser(userName);
        } else {
            return StaticData.users.get(userName);
        }
    }
}
