package project.lighthouse.autotests.pages.catalog;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonApi;

import java.io.IOException;

public class CatalogApi extends CommonApi {

    public CatalogApi(WebDriver driver) {
        super(driver);
    }

    public void createKlassThroughPost(String klassName) throws IOException, JSONException {
        apiConnect.createKlassThroughPost(klassName);
    }

    public void createGroupThroughPost(String groupName, String klassName) throws IOException, JSONException {
        apiConnect.createGroupThroughPost(groupName, klassName);
    }

    public void navigateToKlassPage(String klassName) throws JSONException {
        apiConnect.navigateToKlassPage(klassName);
    }
}
