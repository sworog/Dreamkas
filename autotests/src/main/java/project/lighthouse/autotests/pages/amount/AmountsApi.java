package project.lighthouse.autotests.pages.amount;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

public class AmountsApi extends PageObject {

    ApiConnect apiConnect = new ApiConnect(getDriver());

    public AmountsApi(WebDriver driver) {
        super(driver);
    }

    public void averagePriceCalculation() {
        apiConnect.averagePriceRecalculation();
    }
}
