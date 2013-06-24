package project.lighthouse.autotests.pages.administrator.api;

import net.thucydides.core.pages.PageObject;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

public class AdministratorApi extends PageObject {

    ApiConnect apiConnect;

    public AdministratorApi(WebDriver driver) throws JSONException {
        super(driver);
        apiConnect = new ApiConnect("watchmanApi", "lighthouse");
    }
}
