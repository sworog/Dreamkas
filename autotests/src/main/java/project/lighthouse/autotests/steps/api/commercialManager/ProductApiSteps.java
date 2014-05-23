package project.lighthouse.autotests.steps.api.commercialManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.api.factories.ExtraBarcodeFactory;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.objects.api.product.ExtraBarcode;
import project.lighthouse.autotests.storage.Storage;

import java.io.IOException;
import java.util.List;

public class ProductApiSteps extends CommercialManagerApi {

    @Step
    public Product createProduct(String name,
                                 String type,
                                 String vat,
                                 String purchasePrice,
                                 String barcode,
                                 String vendorCountry,
                                 String vendor,
                                 String subCategoryName,
                                 String retailMarkupMax,
                                 String retailMarkupMin,
                                 String rounding) throws JSONException, IOException {
        SubCategory subCategoryObject = StaticData.subCategories.get(subCategoryName);
        Product product = new Product(
                name,
                type,
                vat,
                purchasePrice,
                barcode,
                vendorCountry,
                vendor,
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
                                            String type,
                                            String purchasePrice,
                                            String subCategoryName,
                                            String rounding) throws JSONException, IOException {
        SubCategory subCategory = StaticData.subCategories.get(subCategoryName);
        apiConnect.getSubCategoryMarkUp(subCategory);
        return createProduct(
                name,
                type,
                "0",
                purchasePrice,
                barcode,
                "Тестовая страна",
                "Тестовый производитель",
                subCategoryName,
                StaticData.retailMarkupMax,
                StaticData.retailMarkupMin,
                rounding);
    }

    @Step
    public Product createProductThroughPost(String name,
                                            String barcode,
                                            String type,
                                            String purchasePrice,
                                            String subCategoryName,
                                            String retailMarkupMax,
                                            String retailMarkupMin,
                                            String rounding) throws JSONException, IOException {
        return createProduct(
                name,
                type,
                "0",
                purchasePrice,
                barcode,
                "Тестовая страна",
                "Тестовый производитель",
                subCategoryName,
                retailMarkupMax,
                retailMarkupMin,
                rounding);
    }

    @Step
    public Product createProductThroughPost(String name,
                                            String barcode,
                                            String type,
                                            String purchasePrice,
                                            String subCategoryName,
                                            String retailMarkupMax,
                                            String retailMarkupMin,
                                            String rounding,
                                            String nameOfScales,
                                            String descriptionOnScales,
                                            String ingredients,
                                            String nutritionFacts,
                                            String shelfLife) throws JSONException, IOException {
        SubCategory subCategoryObject = StaticData.subCategories.get(subCategoryName);
        Product product = new Product(
                name,
                type,
                "0",
                purchasePrice,
                barcode,
                "Тестовая страна",
                "Тестовый производитель",
                subCategoryObject.getId(),
                retailMarkupMax,
                retailMarkupMin,
                rounding,
                nameOfScales,
                descriptionOnScales,
                ingredients,
                nutritionFacts,
                shelfLife);
        apiConnect.getSubCategoryMarkUp(subCategoryObject);
        return apiConnect.createProductThroughPost(product, subCategoryObject);
    }

    @Step
    public Product createProductThroughPost(String name,
                                            String barcode,
                                            String type,
                                            String purchasePrice,
                                            String subCategoryName,
                                            String retailMarkupMax,
                                            String retailMarkupMin,
                                            String rounding,
                                            String alcoholByVolume,
                                            String volume) throws JSONException, IOException {
        SubCategory subCategoryObject = StaticData.subCategories.get(subCategoryName);
        Product product = new Product(
                name,
                type,
                "0",
                purchasePrice,
                barcode,
                "Тестовая страна",
                "Тестовый производитель",
                subCategoryObject.getId(),
                retailMarkupMax,
                retailMarkupMin,
                rounding,
                alcoholByVolume,
                volume);
        apiConnect.getSubCategoryMarkUp(subCategoryObject);
        return apiConnect.createProductThroughPost(product, subCategoryObject);
    }

    @Step
    public void navigateToTheProductPage(String productName) throws JSONException {
        String productPageUrl = apiConnect.getProductPageUrl(productName);
        getDriver().navigate().to(productPageUrl);
    }

    @Step
    public void addProductExtraBarcodes(Product product, List<ExtraBarcode> extraBarcodes) throws IOException, JSONException {
        new ExtraBarcodeFactory("commercialManager", "lighthouse").addProductExtraBarcodes(product, extraBarcodes);
        Storage.getCustomVariableStorage().getExtraBarcodes().clear();
    }
}
