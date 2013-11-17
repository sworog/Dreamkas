package project.lighthouse.autotests.pages.commercialManager.department;

import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Department;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.api.commercialManager.CommercialManagerApi;

import java.io.IOException;

public class DepartmentApi extends CommercialManagerApi {

    public DepartmentApi() throws JSONException {
    }

    public Department createStoreDepartmentThroughPost(String number, String name, String storeName) throws IOException, JSONException {
        String storeId = StaticData.stores.get(storeName).getId();
        Department department = new Department(number, name, storeId);
        return apiConnect.createStoreDepartmentThroughPost(department);
    }

    public Department createStoreDepartmentThroughPost(String number, String name) throws IOException, JSONException {
        if (!StaticData.hasStore(Store.DEFAULT_NUMBER)) {
            apiConnect.createStoreThroughPost(new Store());
        }
        return createStoreDepartmentThroughPost(number, name, Store.DEFAULT_NUMBER);
    }
}

