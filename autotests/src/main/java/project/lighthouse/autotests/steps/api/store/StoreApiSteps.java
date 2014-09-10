package project.lighthouse.autotests.steps.api.store;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;
import project.lighthouse.autotests.storage.variable.CustomVariableStorage;

import java.io.IOException;

public class StoreApiSteps extends ScenarioSteps {

    @Step
    public Store createStoreByUserWithEmail(String name, String address, String email) throws JSONException, IOException {
        Store store = new Store(name, address);
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        CustomVariableStorage customVariableStorage = Storage.getCustomVariableStorage();

        if (!customVariableStorage.getStores().containsKey(store.getName())) {
            new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(store);
            customVariableStorage.getStores().put(store.getName(), store);
            Storage.getStoreVariableStorage().setStore(store);
            userContainer.setStore(store);
            return store;
        } else {
            return customVariableStorage.getStores().get(store.getName());
        }
    }
}
