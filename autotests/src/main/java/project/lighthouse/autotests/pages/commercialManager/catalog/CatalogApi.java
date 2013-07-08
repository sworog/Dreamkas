package project.lighthouse.autotests.pages.commercialManager.catalog;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.commercialManager.api.CommercialManagerApi;

import java.io.IOException;

public class CatalogApi extends CommercialManagerApi {

    public CatalogApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public void createGroupThroughPost(String groupName) throws IOException, JSONException {
        apiConnect.createGroupThroughPost(groupName);
    }

    public void createCategoryThroughPost(String categoryName, String groupName) throws IOException, JSONException {
        apiConnect.createCategoryThroughPost(categoryName, groupName);
    }

    public void navigateToGroupPage(String groupName) throws JSONException {
        String klassPageUrl = apiConnect.getGroupPageUrl(groupName);
        getDriver().navigate().to(klassPageUrl);
    }
}
