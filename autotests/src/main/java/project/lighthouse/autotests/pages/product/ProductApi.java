package project.lighthouse.autotests.pages.product;

import net.thucydides.core.pages.PageObject;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

import java.io.IOException;

public class ProductApi extends PageObject {

    ApiConnect apiConnect = new ApiConnect(getDriver());

    public ProductApi(WebDriver driver) {
        super(driver);
    }

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        apiConnect.сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }
}
