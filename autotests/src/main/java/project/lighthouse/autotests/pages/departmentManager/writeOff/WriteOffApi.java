package project.lighthouse.autotests.pages.departmentManager.writeOff;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.objects.User;
import project.lighthouse.autotests.objects.WriteOff;
import project.lighthouse.autotests.pages.administrator.users.UserApi;
import project.lighthouse.autotests.pages.commercialManager.catalog.CatalogApi;
import project.lighthouse.autotests.pages.commercialManager.store.StoreApi;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;

import java.io.IOException;

public class WriteOffApi extends DepartmentManagerApi {

    StoreApi storeApi = new StoreApi(getDriver());
    UserApi userApi = new UserApi(getDriver());
    CatalogApi catalogApi = new CatalogApi(getDriver());

    public WriteOffApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public WriteOff createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        Store store = storeApi.createStoreThroughPost();
        User user = userApi.getUser(DEFAULT_USER_NAME);
        catalogApi.promoteDepartmentManager(store, user.getUserName());
        return createWriteOffThroughPost(writeOffNumber, store.getNumber(), user.getUserName());
    }

    public WriteOff createWriteOffThroughPost(String writeOffNumber, String storeName, String userName) throws JSONException, IOException {
        WriteOff writeOff = new WriteOff(writeOffNumber, DateTime.getTodayDate(DateTime.DATE_PATTERN));
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
