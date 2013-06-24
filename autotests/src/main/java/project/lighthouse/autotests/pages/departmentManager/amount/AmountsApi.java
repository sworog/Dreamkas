package project.lighthouse.autotests.pages.departmentManager.amount;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;

import java.io.IOException;

public class AmountsApi extends DepartmentManagerApi {

    public AmountsApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public void averagePriceCalculation() throws IOException, JSONException {
        apiConnect.averagePriceRecalculation();
    }
}
