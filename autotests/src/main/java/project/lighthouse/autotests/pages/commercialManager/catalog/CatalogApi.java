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
        String groupPageUrl = apiConnect.getGroupPageUrl(groupName);
        getDriver().navigate().to(groupPageUrl);
    }

    public void navigateToCategoryPage(String categoryName, String groupName) throws JSONException {
        String categoryPageUrl = apiConnect.getCategoryPageUrl(categoryName, groupName);
        getDriver().navigate().to(categoryPageUrl);
    }

    public void createSubCategoryThroughPost(String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        apiConnect.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
    }

    public void navigateToSubCategoryProductListPageUrl(String subCategoryName, String categoryName, String groupName) throws JSONException {
        String subCategoryProductListPageUrl = apiConnect.getSubCategoryProductListPageUrl(subCategoryName, categoryName, groupName);
        getDriver().navigate().to(subCategoryProductListPageUrl);
    }

    public void navigateToSubCategoryProductListPageUrlWihEditModeOn(String subCategoryName, String categoryName, String groupName) throws JSONException {
        String subCategoryProductListPageUrl = apiConnect.getSubCategoryProductListPageUrl(subCategoryName, categoryName, groupName) + "?editMode=true";
        getDriver().navigate().to(subCategoryProductListPageUrl);
    }

    public void navigateToSubCategoryProductCreatePageUrl(String subCategoryName) throws JSONException {
        String subCategoryProductCreatePageUrl = apiConnect.getSubCategoryProductCreatePageUrl(subCategoryName);
        getDriver().navigate().to(subCategoryProductCreatePageUrl);
    }
}
