package project.lighthouse.autotests.pages.departmentManager.writeOff;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.User;
import project.lighthouse.autotests.objects.api.WriteOff;
import project.lighthouse.autotests.pages.commercialManager.catalog.CatalogApi;
import project.lighthouse.autotests.pages.commercialManager.store.StoreApi;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;

import java.io.IOException;
import java.util.Map;

public class WriteOffApi extends DepartmentManagerApi {

    StoreApi storeApi = new StoreApi(getDriver());
    UserApiSteps userApiSteps = new UserApiSteps();
    CatalogApi catalogApi = new CatalogApi(getDriver());

    public WriteOffApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public WriteOff createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        Store store = storeApi.createStoreThroughPost();
        User user = userApiSteps.getUser(DEFAULT_USER_NAME);
        catalogApi.promoteDepartmentManager(store, user.getUserName());
        return createWriteOffThroughPost(writeOffNumber, store.getNumber(), user.getUserName());
    }

    public WriteOff createWriteOffThroughPost(String writeOffNumber, String storeName, String userName) throws JSONException, IOException {
        WriteOff writeOff = new WriteOff(writeOffNumber, DateTime.getTodayDate(DateTime.DATE_PATTERN));
        String storeId = StaticData.stores.get(storeName).getId();
        writeOff.setStoreId(storeId);
        return new ApiConnect(userName, "lighthouse").createWriteOffThroughPost(writeOff);
    }

    public WriteOff createWriteOffThrougPost(String storeName, String userName, ExamplesTable examplesTable) throws JSONException, IOException {
        String number = "", date = "";
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("elementValue");
            switch (elementName) {
                case "number":
                    number = elementValue;
                    break;
                case "date":
                    date = elementValue;
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }
        WriteOff writeOff = new WriteOff(number, date);
        String storeId = StaticData.stores.get(storeName).getId();
        writeOff.setStoreId(storeId);
        return new ApiConnect(userName, "lighthouse").createWriteOffThroughPost(writeOff);
    }

    public WriteOff createWriteOffThroughPost(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws IOException, JSONException {
        WriteOff writeOff = createWriteOffThroughPost(writeOffNumber);
        addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause, DEFAULT_USER_NAME);
        return writeOff;
    }

    //TODO given method
    public WriteOff createWriteOffThroughPost(String writeOffNumber, String productSku, String quantity, String price, String cause, String storeName, String userName) throws IOException, JSONException {
        WriteOff writeOff = createWriteOffThroughPost(writeOffNumber, storeName, userName);
        addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause, userName);
        return writeOff;
    }

    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause, String userName) throws JSONException, IOException {
        new ApiConnect(userName, "lighthouse").addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws JSONException, IOException {
        createWriteOffThroughPost(writeOffNumber, productSku, quantity, price, cause);
        navigateToWriteOffPage(writeOffNumber);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber)
            throws JSONException, IOException {
        createWriteOffThroughPost(writeOffNumber);
        navigateToWriteOffPage(writeOffNumber);
    }

    public void navigateToWriteOffPage(String writeOffNumber) throws JSONException {
        String writeOffPageUrl = apiConnect.getWriteOffPageUrl(writeOffNumber);
        getDriver().navigate().to(writeOffPageUrl);
    }
}
