package project.lighthouse.autotests.pages.commercialManager.product;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.commercialManager.api.CommercialManagerApi;

import java.io.IOException;

public class ProductApi extends CommercialManagerApi {

    public ProductApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        apiConnect.сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }

    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice, String subCategoryName) throws JSONException, IOException {
        apiConnect.createProductThroughPost(name, sku, barcode, units, purchasePrice, subCategoryName);
    }

    public void navigateToTheProductPage(String productSku) throws JSONException {
        String productPageUrl = apiConnect.getProductPageUrl(productSku);
        getDriver().navigate().to(productPageUrl);
    }
}
