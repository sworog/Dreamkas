package project.lighthouse.autotests.pages.commercialManager.catalog;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.commercialManager.api.CommercialManagerApi;

import java.io.IOException;

public class CatalogApi extends CommercialManagerApi {

    public CatalogApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public void createKlassThroughPost(String klassName) throws IOException, JSONException {
        apiConnect.createKlassThroughPost(klassName);
    }

    public void createGroupThroughPost(String groupName, String klassName) throws IOException, JSONException {
        apiConnect.createGroupThroughPost(groupName, klassName);
    }

    public void navigateToKlassPage(String klassName) throws JSONException {
        String klassPageUrl = apiConnect.getKlassPageUrl(klassName);
        getDriver().navigate().to(klassPageUrl);
    }
}
