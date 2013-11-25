package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;

import java.io.IOException;

public class EndUserApiSteps {

    @Steps
    UserApiSteps userApiSteps;


    @Given("there is the user with name '$name', position '$position', username '$userName', password '$password', role '$role'")
    @Alias("there is the user with name '$name', position '$position', <userName>, password '$password', role '$role'")
    public void givenThereIsTheUser(String name, String position, String userName, String password, String role) throws IOException, JSONException {
        userApiSteps.createUserThroughPost(name, position, userName, password, role);
    }

    @Given("there is the user with <userName>, password '$password', role '$role'")
    public void givenThereIsTheUser(String userName, String password, String role) throws IOException, JSONException {
        userApiSteps.createUserThroughPost(userName, userName, userName, password, role);
    }

    @Given("the user navigates to the user page with username '$login'")
    public void givenTheUserNavigatesToTheUserPage(String login) throws JSONException {
        userApiSteps.navigateToTheUserPage(login);
    }
}
