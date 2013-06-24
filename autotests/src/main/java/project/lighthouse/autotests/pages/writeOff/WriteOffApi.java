package project.lighthouse.autotests.pages.writeOff;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonApi;

import java.io.IOException;

public class WriteOffApi extends CommonApi {

    public WriteOffApi(WebDriver driver) {
        super(driver);
    }

    public void createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        apiConnect.createWriteOffThroughPost(writeOffNumber);
    }

    public void createWriteOffThroughPost(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                          String quantity, String price, String cause)
            throws IOException, JSONException {
        apiConnect.createWriteOffThroughPost(writeOffNumber, productName, productSku, productBarCode, productUnits, purchasePrice, quantity, price, cause);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                              String quantity, String price, String cause)
            throws JSONException, IOException {
        apiConnect.createWriteOffThroughPost(writeOffNumber, productName, productSku, productBarCode, productUnits, purchasePrice, quantity, price, cause);
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
