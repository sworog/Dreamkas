package project.lighthouse.autotests.steps.api.store;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.ApiConnect;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

public class StoreApiSteps extends ScenarioSteps {

    @Step
    public Store createStoreByUserWithEmail(String name, String address, String email) throws JSONException, IOException {
        Store store = new Store(name, address);
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);

        store = new ApiConnect(userContainer.getEmail(), userContainer.getPassword()).createStoreThroughPost(store);
        Storage.getStoreVariableStorage().setStore(store);
        userContainer.setStore(store);
        return store;
    }
}
