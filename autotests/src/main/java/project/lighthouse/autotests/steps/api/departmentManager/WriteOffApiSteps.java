package project.lighthouse.autotests.steps.api.departmentManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.api.ApiConnect;
import project.lighthouse.autotests.objects.api.WriteOff;

import java.io.IOException;

public class WriteOffApiSteps extends DepartmentManagerApi {

    @Step
    public WriteOff createWriteOffThroughPost(String writeOffNumber, String date, String storeName, String userName) throws JSONException, IOException {
        WriteOff writeOff = new WriteOff(writeOffNumber, date);
        String storeId = StaticData.stores.get(storeName).getId();
        writeOff.setStoreId(storeId);
        return new ApiConnect(userName, "lighthouse").createWriteOffThroughPost(writeOff);
    }

    @Step
    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause, String userName) throws JSONException, IOException {
        new ApiConnect(userName, "lighthouse").addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause);
    }

    @Step
    public void navigateToWriteOffPage(String writeOffNumber) throws JSONException {
        String writeOffPageUrl = apiConnect.getWriteOffPageUrl(writeOffNumber);
        getDriver().navigate().to(writeOffPageUrl);
    }
}
