package project.lighthouse.autotests.pages.catalog;

import net.thucydides.core.pages.PageObject;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

import java.io.IOException;

public class CatalogApi extends PageObject {

    ApiConnect apiConnect = new ApiConnect(getDriver());

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
