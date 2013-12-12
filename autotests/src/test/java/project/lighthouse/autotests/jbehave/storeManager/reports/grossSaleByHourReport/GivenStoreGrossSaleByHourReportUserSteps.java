package project.lighthouse.autotests.jbehave.storeManager.reports.grossSaleByHourReport;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.storeManager.reports.StoreGrossSaleByHourReportSteps;

public class GivenStoreGrossSaleByHourReportUserSteps {

    @Steps
    StoreGrossSaleByHourReportSteps storeGrossSaleByHourReportSteps;

    @Steps
    StoreApiSteps storeApiSteps;

    @Given("the user opens gross sale by hours report page of store number '$storeNumber'")
    public void givenTheUserOpensGrossSaleByHourReportPage(String storeNumber) throws JSONException {
        String grossSaleByHourReport = String.format("%s/stores/%s/reports/grossSalesByHours", UrlHelper.getWebFrontUrl(), storeApiSteps.getStoreId(storeNumber));
        storeGrossSaleByHourReportSteps.getDriver().navigate().to(grossSaleByHourReport);
    }
}
