package project.lighthouse.autotests.pages.departmentManager.writeOff;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;

import java.io.IOException;

public class WriteOffApi extends DepartmentManagerApi {

    public WriteOffApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public void createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        apiConnect.createWriteOffThroughPost(writeOffNumber);
    }

    public void createWriteOffThroughPost(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws IOException, JSONException {
        apiConnect.createWriteOffThroughPost(writeOffNumber, productSku, quantity, price, cause);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws JSONException, IOException {
        apiConnect.createWriteOffThroughPost(writeOffNumber, productSku, quantity, price, cause);
        navigatoToWriteOffPage(writeOffNumber);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber)
            throws JSONException, IOException {
        apiConnect.createWriteOffThroughPost(writeOffNumber);
        navigatoToWriteOffPage(writeOffNumber);
    }

    public void navigatoToWriteOffPage(String writeOffNumber) throws JSONException {
        String writeOffPageUrl = apiConnect.getWriteOffPageUrl(writeOffNumber);
        getDriver().navigate().to(writeOffPageUrl);
    }
}
