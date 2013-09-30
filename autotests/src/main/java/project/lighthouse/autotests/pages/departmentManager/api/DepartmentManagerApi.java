package project.lighthouse.autotests.pages.departmentManager.api;

import net.thucydides.core.pages.PageObject;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

public class DepartmentManagerApi extends PageObject {

    protected ApiConnect apiConnect;

    public static final String DEFAULT_USER_NAME = "departmentManager";

    public DepartmentManagerApi(WebDriver driver) throws JSONException {
        super(driver);
        apiConnect = new ApiConnect("departmentManagerApi", "lighthouse");
    }
}
