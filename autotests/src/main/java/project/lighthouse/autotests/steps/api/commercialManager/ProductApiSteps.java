package project.lighthouse.autotests.steps.api.commercialManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;

import java.io.IOException;

//TODO move all logic to high-level class
public class ProductApiSteps extends CommercialManagerApi {

    @Step
    public Product createProduct(String name,
                                 String units,
                                 String vat,
                                 String purchasePrice,
                                 String barcode,
                                 String vendorCountry,
                                 String vendor,
                                 String info,
                                 String subCategoryName,
                                 String retailMarkupMax,
                                 String retailMarkupMin,
                                 String rounding) throws JSONException, IOException {
        SubCategory subCategoryObject = StaticData.subCategories.get(subCategoryName);
        Product product = new Product(
                name,
                units,
                vat,
                purchasePrice,
                barcode,
                vendorCountry,
                vendor,
                info,
                subCategoryObject.getId(),
                retailMarkupMax,
                retailMarkupMin,
                rounding);
        apiConnect.getSubCategoryMarkUp(subCategoryObject);
        return apiConnect.createProductThroughPost(product, subCategoryObject);
    }

    @Step
    public Product createProductThroughPost(String name,
                                            String barcode,
                                            String units,
                                            String purchasePrice,
                                            String subCategoryName) throws JSONException, IOException {
        return createProductThroughPost(name, barcode, units, purchasePrice, subCategoryName, null);
    }

    @Step
    public Product createProductThroughPost(String name,
                                            String barcode,
                                            String units,
                                            String purchasePrice,
                                            String subCategoryName,
                                            String rounding) throws JSONException, IOException {
        SubCategory subCategory = StaticData.subCategories.get(subCategoryName);
        apiConnect.getSubCategoryMarkUp(subCategory);
        Product product = new Product(name, units, "0", purchasePrice, barcode, "Тестовая страна", "Тестовый производитель", "", subCategory.getId(), StaticData.retailMarkupMax, StaticData.retailMarkupMin, rounding);
        return apiConnect.createProductThroughPost(product, subCategory);
    }

    @Step
    public Product createProductThroughPost(String name,
                                            String barcode,
                                            String units,
                                            String purchasePrice,
                                            String subCategoryName,
                                            String retailMarkupMax,
                                            String retailMarkupMin,
                                            String rounding) throws JSONException, IOException {
        SubCategory subCategory = StaticData.subCategories.get(subCategoryName);
        Product product = new Product(name, units, "0", purchasePrice, barcode, "Тестовая страна", "Тестовый производитель", "", subCategory.getId(), retailMarkupMax, retailMarkupMin, rounding);
        return apiConnect.createProductThroughPost(product, subCategory);
    }

    @Step
    public void navigateToTheProductPage(String productName) throws JSONException {
        String productPageUrl = apiConnect.getProductPageUrl(productName);
        getDriver().navigate().to(productPageUrl);
    }
}
