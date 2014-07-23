package project.lighthouse.autotests.steps.api.catalog;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.ApiConnect;
import project.lighthouse.autotests.objects.api.Group;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

public class CatalogApiSteps extends ScenarioSteps {

    @Step
    public Group createGroupThroughPostByUserWithEmail(String groupName, String email) throws IOException, JSONException {
        Group group = new Group(groupName);
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        return new ApiConnect(userContainer.getEmail(), userContainer.getPassword()).createGroupThroughPost(group);
    }
}
