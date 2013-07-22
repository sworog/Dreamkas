package project.lighthouse.autotests.pages.commercialManager.department;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.pages.commercialManager.api.CommercialManagerApi;

import java.io.IOException;

public class DepartmentApi extends CommercialManagerApi {

    public DepartmentApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public void createStoreDepartmentThroughPost(String number, String name, String store) throws IOException, JSONException {
        apiConnect.createStoreDepartmentThroughPost(number, name, store);
    }

    public void createStoreDepartmentThroughPost(String number, String name) throws IOException, JSONException {
        apiConnect.createStoreDepartmentThroughPost(number, name);
    }
}

