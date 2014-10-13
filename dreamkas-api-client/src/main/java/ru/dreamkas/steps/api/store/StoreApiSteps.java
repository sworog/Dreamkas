package ru.dreamkas.steps.api.store;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.Store;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.containers.user.UserContainer;
import ru.dreamkas.apiStorage.variable.CustomVariableStorage;

import java.io.IOException;

public class StoreApiSteps extends ScenarioSteps {

    @Step
    public Store createStoreByUserWithEmail(String name, String address, String email) throws JSONException, IOException {
        Store store = new Store(name, address);
        UserContainer userContainer = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        CustomVariableStorage customVariableStorage = ApiStorage.getCustomVariableStorage();

        if (!customVariableStorage.getStores().containsKey(store.getName())) {
            new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(store);
            customVariableStorage.getStores().put(store.getName(), store);
            userContainer.setStore(store);
            return store;
        } else {
            return customVariableStorage.getStores().get(store.getName());
        }
    }
}
