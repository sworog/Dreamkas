package project.lighthouse.autotests.steps.api.commercialManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Department;
import project.lighthouse.autotests.objects.api.Store;

import java.io.IOException;

public class DepartmentApiSteps extends CommercialManagerApi {

    @Step
    public Department createStoreDepartmentThroughPost(String number, String name, String storeName) throws IOException, JSONException {
        String storeId = StaticData.stores.get(storeName).getId();
        Department department = new Department(number, name, storeId);
        return apiConnect.createStoreDepartmentThroughPost(department);
    }

    @Step
    public Department createStoreDepartmentThroughPost(String number, String name) throws IOException, JSONException {
        if (!StaticData.hasStore(Store.DEFAULT_NUMBER)) {
            apiConnect.createStoreThroughPost(new Store());
        }
        return createStoreDepartmentThroughPost(number, name, Store.DEFAULT_NUMBER);
    }
}

