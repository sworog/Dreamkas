package project.lighthouse.autotests.pages.commercialManager.api;

import net.thucydides.core.pages.PageObject;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

public class CommercialManagerApi extends PageObject {

    protected ApiConnect apiConnect;

    public CommercialManagerApi(WebDriver driver) throws JSONException {
        super(driver);
        apiConnect = new ApiConnect("commercialManagerApi", "lighthouse");
    }
}
