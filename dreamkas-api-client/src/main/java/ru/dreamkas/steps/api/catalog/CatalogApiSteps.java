package ru.dreamkas.steps.api.catalog;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.SubCategory;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.containers.user.UserContainer;
import ru.dreamkas.apiStorage.variable.CustomVariableStorage;

import java.io.IOException;

public class CatalogApiSteps extends ScenarioSteps {

    @Step
    public SubCategory createGroupThroughPostByUserWithEmail(String groupName, String email) throws IOException, JSONException {
        SubCategory subCategory = new SubCategory(groupName);
        UserContainer userContainer = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        CustomVariableStorage customVariableStorage = ApiStorage.getCustomVariableStorage();

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
