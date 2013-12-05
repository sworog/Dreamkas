package project.lighthouse.autotests.jbehave.commercialManager.reports.grossSaleByNetwork;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.commercialManager.reports.GrossSaleByNetworkSteps;

public class ThenGrossSaleByNetworkUserSteps {

    @Steps
    GrossSaleByNetworkSteps grossSaleByNetworkSteps;

    @Then("the user checks the gross sale by network yesterday value")
    public void thenTheUserChecksTheGrossSaleByNetworkYesterdayValue() {
        grossSaleByNetworkSteps.assertYesterdayValue();
    }

    @Then("the user checks the gross sale by network two days value")
    public void thenTheUserChecksTheGrossSaleByNetworkTwoDaysAgoValue() {
        grossSaleByNetworkSteps.assertTwoDaysAgoValue();
    }

    @Then("the user checks the gross sale by network eight days value")
    public void thenTheUserChecksTheGrossSaleByNetworkEightDaysAgoValue() {
        grossSaleByNetworkSteps.assertEightDaysAgoValue();
    }
}
