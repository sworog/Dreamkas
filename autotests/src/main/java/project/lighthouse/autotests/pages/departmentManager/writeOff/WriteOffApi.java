package project.lighthouse.autotests.pages.departmentManager.writeOff;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.WriteOff;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;

import java.io.IOException;

public class WriteOffApi extends DepartmentManagerApi {

    public WriteOffApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public WriteOff createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        WriteOff writeOff = new WriteOff(writeOffNumber, DateTime.getTodayDate(DateTime.DATE_PATTERN));
        return apiConnect.createWriteOffThroughPost(writeOff);
    }

    public WriteOff createWriteOffThroughPost(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws IOException, JSONException {
        WriteOff writeOff = createWriteOffThroughPost(writeOffNumber);
        apiConnect.addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause);
        return writeOff;
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws JSONException, IOException {
        createWriteOffThroughPost(writeOffNumber, productSku, quantity, price, cause);
        navigatoToWriteOffPage(writeOffNumber);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber)
            throws JSONException, IOException {
        createWriteOffThroughPost(writeOffNumber);
        navigatoToWriteOffPage(writeOffNumber);
    }

    public void navigatoToWriteOffPage(String writeOffNumber) throws JSONException {
        String writeOffPageUrl = apiConnect.getWriteOffPageUrl(writeOffNumber);
        getDriver().navigate().to(writeOffPageUrl);
    }
}
