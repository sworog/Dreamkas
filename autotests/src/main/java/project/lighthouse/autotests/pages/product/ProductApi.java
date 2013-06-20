package project.lighthouse.autotests.pages.product;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonApi;

import java.io.IOException;

public class ProductApi extends CommonApi {

    public ProductApi(WebDriver driver) {
        super(driver);
    }

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        apiConnect.сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }
}
