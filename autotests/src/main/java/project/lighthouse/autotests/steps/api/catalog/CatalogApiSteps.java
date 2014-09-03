package project.lighthouse.autotests.steps.api.catalog;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;
import project.lighthouse.autotests.storage.variable.CustomVariableStorage;

import java.io.IOException;

public class CatalogApiSteps extends ScenarioSteps {

    @Step
    public SubCategory createGroupThroughPostByUserWithEmail(String groupName, String email) throws IOException, JSONException {
        SubCategory subCategory = new SubCategory(groupName);
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        CustomVariableStorage customVariableStorage = Storage.getCustomVariableStorage();

        if (!customVariableStorage.getSubCategories().containsKey(subCategory.getName())) {
            new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(subCategory);
            customVariableStorage.getSubCategories().put(subCategory.getName(), subCategory);
            return subCategory;
        } else {
            return customVariableStorage.getSubCategories().get(subCategory.getName());
        }
    }

    @Step
    public void navigateToGroupPage(String groupName) throws JSONException {
        String groupPageUrl = SubCategory.getPageUrl(groupName);
        getDriver().navigate().to(groupPageUrl);
    }
}
