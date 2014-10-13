package ru.dreamkas.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import ru.dreamkas.steps.api.catalog.CatalogApiSteps;

import java.io.IOException;

public class GivenCatalogApiUserSteps {

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Given("the user with email '$email' creates group with name '$groupName'")
    @Alias("пользователь с адресом электронной почты '$email' создает группу с именем '$groupName'")
    public void givenTheUserWithEmailCreatesGroupWithName(String email, String groupName) throws IOException, JSONException {
        catalogApiSteps.createGroupThroughPostByUserWithEmail(groupName, email);
    }

    @Given("the user navigates to the group with name '$groupName'")
    @Alias("пользователь открывает страницу группы с названием '$groupName'")
    public void givenTheUserNavigatesToTheGroup(String groupName) throws JSONException {
        catalogApiSteps.navigateToGroupPage(groupName);
    }
}
