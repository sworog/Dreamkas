package project.lighthouse.autotests.steps.api.commercialManager;

import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Category;
import project.lighthouse.autotests.objects.api.Group;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;

import java.io.IOException;

//TODO move all logic to high-level class
public class ProductApiSteps extends CommercialManagerApi {

    public ProductApiSteps() throws JSONException {
    }

    public Product createProductThroughPost(Product product, SubCategory subCategory) throws IOException, JSONException {
        return apiConnect.createProductThroughPost(product, subCategory);
    }

    public Product createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                            String subCategoryName) throws JSONException, IOException {
        return createProductThroughPostDefault(name, sku, barcode, units, purchasePrice, Group.DEFAULT_NAME, Category.DEFAULT_NAME, subCategoryName, null);
    }

    public Product createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                            String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        return createProductThroughPostDefault(name, sku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName, null);
    }

    public Product createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                            String groupName, String categoryName, String subCategoryName, String rounding) throws IOException, JSONException {
        return createProductThroughPostDefault(name, sku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName, rounding);
    }

    public Product createProductThroughPostDefault(String name, String sku, String barcode, String units, String purchasePrice, String groupName, String categoryName, String subCategoryName, String rounding) throws JSONException, IOException {
        SubCategory subCategory = StaticData.subCategories.get(subCategoryName);
        apiConnect.getSubCategoryMarkUp(subCategory);
        Product product = new Product(name, units, "0", purchasePrice, barcode, sku, "Тестовая страна", "Тестовый производитель", "", subCategory.getId(), StaticData.retailMarkupMax, StaticData.retailMarkupMin, rounding);
        return apiConnect.createProductThroughPost(product, subCategory);
    }

    public Product createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice, String groupName, String categoryName, String subCategoryName, String retailMarkupMax, String retailMarkupMin, String rounding) throws JSONException, IOException {
        SubCategory subCategory = StaticData.subCategories.get(subCategoryName);
        Product product = new Product(name, units, "0", purchasePrice, barcode, sku, "Тестовая страна", "Тестовый производитель", "", subCategory.getId(), retailMarkupMax, retailMarkupMin, rounding);
        return apiConnect.createProductThroughPost(product, subCategory);
    }

    public void navigateToTheProductPage(String productSku) throws JSONException {
        String productPageUrl = apiConnect.getProductPageUrl(productSku);
        getDriver().navigate().to(productPageUrl);
    }
}
