package project.lighthouse.autotests.api.factories;

import org.json.JSONException;
import project.lighthouse.autotests.api.abstractFactory.AbstractApiFactory;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.storage.Storage;

import java.io.IOException;

public class StoreFactory extends AbstractApiFactory {

    public StoreFactory(String email, String password) {
        super(email, password);
    }

    public Store create(Store store) throws JSONException, IOException {
        createObject(store);
        Storage.getStoreVariableStorage().setStore(store);
        return store;
    }
}
