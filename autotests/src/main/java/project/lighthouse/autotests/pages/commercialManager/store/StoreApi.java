package project.lighthouse.autotests.pages.commercialManager.store;

import org.json.JSONException;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.api.commercialManager.CommercialManagerApi;

import java.io.IOException;

public class StoreApi extends CommercialManagerApi {

    public StoreApi() throws JSONException {
    }

    public Store createStoreThroughPost() throws IOException, JSONException {
        return apiConnect.createStoreThroughPost(new Store());
    }

    public Store createStoreThroughPost(String number, String address, String contacts) throws IOException, JSONException {
        Store store = new Store(number, address, contacts);
        return apiConnect.createStoreThroughPost(store);
    }

    public String getStoreId(String storeNumber) throws JSONException {
        return apiConnect.getStoreId(storeNumber);
    }
}
