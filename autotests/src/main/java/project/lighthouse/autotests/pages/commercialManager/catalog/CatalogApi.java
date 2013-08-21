package project.lighthouse.autotests.pages.commercialManager.catalog;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.Category;
import project.lighthouse.autotests.objects.Group;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.objects.SubCategory;
import project.lighthouse.autotests.pages.commercialManager.api.CommercialManagerApi;

import java.io.IOException;

public class CatalogApi extends CommercialManagerApi {

    public CatalogApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public Group createGroupThroughPost(String groupName) throws IOException, JSONException {
        Group group = new Group(groupName);
        return apiConnect.createGroupThroughPost(group);
    }

    public Category createCategoryThroughPost(String categoryName, String groupName) throws IOException, JSONException {
        Group group = createGroupThroughPost(groupName);
        Category category = new Category(categoryName, group.getId());
        return apiConnect.createCategoryThroughPost(category, group);
    }

    public void navigateToGroupPage(String groupName) throws JSONException {
        String groupPageUrl = apiConnect.getGroupPageUrl(groupName);
        getDriver().navigate().to(groupPageUrl);
    }

    public void navigateToCategoryPage(String categoryName, String groupName) throws JSONException {
        String categoryPageUrl = apiConnect.getCategoryPageUrl(categoryName, groupName);
        getDriver().navigate().to(categoryPageUrl);
    }

    public SubCategory createSubCategoryThroughPost(String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        Group group = createGroupThroughPost(groupName);
        Category category = createCategoryThroughPost(categoryName, groupName);
        SubCategory subCategory = new SubCategory(subCategoryName, category.getId());
        return apiConnect.createSubCategoryThroughPost(subCategory, category, group);
    }

    public SubCategory createSubCategoryThroughPost(String groupName, String categoryName, String subCategoryName, String rounding) throws IOException, JSONException {
        Group group = createGroupThroughPost(groupName);
        Category category = createCategoryThroughPost(categoryName, groupName);
        SubCategory subCategory = new SubCategory(subCategoryName, category.getId(), rounding);
        return apiConnect.createSubCategoryThroughPost(subCategory, category, group);
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

    public void setSubCategoryMarkUp(String retailMarkupMax, String retailMarkupMin, String subCategoryName) throws IOException, JSONException {
        apiConnect.setSubCategoryMarkUp(retailMarkupMax, retailMarkupMin, StaticData.subCategories.get(subCategoryName));
    }

    public void promoteStoreManager(Store store, String userName) throws IOException, JSONException {
        apiConnect.promoteStoreManager(store, StaticData.users.get(userName));
    }

    public SubCategory createDefaultSubCategoryThroughPost() throws IOException, JSONException {
        return createSubCategoryThroughPost(Group.DEFAULT_NAME, Category.DEFAULT_NAME, SubCategory.DEFAULT_NAME);
    }
}
