package project.lighthouse.autotests.pages.amount;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonApi;

public class AmountsApi extends CommonApi {

    public AmountsApi(WebDriver driver) {
        super(driver);
    }

    public void averagePriceCalculation() {
        apiConnect.averagePriceRecalculation();
    }
}
