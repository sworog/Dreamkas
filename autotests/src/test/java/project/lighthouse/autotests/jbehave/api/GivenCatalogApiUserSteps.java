package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.catalog.CatalogApiSteps;

import java.io.IOException;

public class GivenCatalogApiUserSteps {

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Given("the user with email '$email' creates group with name '$groupName'")
    public void givenTheUserWithEmailCreatesGroupWithName(String email, String groupName) throws IOException, JSONException {
        catalogApiSteps.createGroupThroughPostByUserWithEmail(groupName, email);
    }
}
